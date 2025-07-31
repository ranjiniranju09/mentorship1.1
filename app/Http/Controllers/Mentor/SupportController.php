<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Ticketcategory;
use App\TicketDescription;


class SupportController extends Controller
{

    // public function mentortickets()
    // {
    //     //$tickets=TicketDescription::all();
    //     $tickets = TicketDescription::where('user_id', Auth::id())->get();
    //     //return $tickets;
    //     return view('mentor.support.index' ,compact('tickets'));
    // }
    public function mentormappedtickets()
    {
        $loggedInUserId = Auth::id(); // Step 1

        // Step 2: Get mentor ID from mentors table
        $mentor = DB::table('mentors')
            ->where('user_id', $loggedInUserId)
            ->whereNull('deleted_at')
            ->first();

        if (!$mentor) {
            return redirect()->back()->with('error', 'Mentor not found.');
        }

        // Step 3: Get mapped mentee IDs from mappings
        $mappedMenteeIds = DB::table('mappings')
            ->where('mentorname_id', $mentor->id)
            ->whereNull('deleted_at')
            ->pluck('menteename_id');

        // Step 4: Get mentee user IDs from mentees table using mapped mentee IDs
        $menteeUserIds = DB::table('mentees')
            ->whereIn('id', $mappedMenteeIds)
            ->whereNull('deleted_at')
            ->pluck('user_id');

        // Step 5: Get tickets where ticket_descriptions.user_id matches mentee user_ids
        $tickets = DB::table('ticket_descriptions')
            ->join('ticketcategories', 'ticket_descriptions.ticket_category_id', '=', 'ticketcategories.id')
            ->join('users', 'ticket_descriptions.user_id', '=', 'users.id')
            ->leftJoin('ticket_responses', 'ticket_responses.ticket_description_id', '=', 'ticket_descriptions.id')
            ->select(
                'ticket_descriptions.id',
                'ticket_descriptions.ticket_title',
                'ticket_descriptions.ticket_description',
                'ticket_descriptions.created_at',
                'ticket_descriptions.attachment_url',
                'ticketcategories.category_description as category',
                'users.name as user_name',
                'ticket_responses.ticket_response as response',  // ✅ Add this line
                'ticket_responses.created_at as resolved_on'
            )
            ->whereIn('ticket_descriptions.user_id', $menteeUserIds)
            ->whereNull('ticket_descriptions.deleted_at')
            ->orderByDesc('ticket_descriptions.created_at')
            ->get();

        $mentorTickets = DB::table('ticket_descriptions')
            ->join('ticketcategories', 'ticket_descriptions.ticket_category_id', '=', 'ticketcategories.id')
            ->join('users', 'ticket_descriptions.user_id', '=', 'users.id')
            ->leftJoin('ticket_responses', 'ticket_responses.ticket_description_id', '=', 'ticket_descriptions.id')
            ->select(
                'ticket_descriptions.id',
                'ticket_descriptions.ticket_title',
                'ticket_descriptions.ticket_description',
                'ticket_descriptions.created_at',
                'ticket_descriptions.attachment_url',
                'ticketcategories.category_description as category',
                'users.name as user_name',
                'ticket_responses.ticket_response as response',
                'ticket_responses.created_at as resolved_on'
            )
            ->where('ticket_descriptions.user_id', $loggedInUserId) // 👈 Only tickets by logged-in user
            ->whereNull('ticket_descriptions.deleted_at')
            ->orderByDesc('ticket_descriptions.created_at')
            ->get();



        return view('mentor.support.index', compact('tickets','mentorTickets'));
    }


    public function mentorticketscreate()
    {
        //$ticket_categories=Ticketcategory::all();
        $ticket_categories = Ticketcategory::pluck('category_description', 'id');
        return view('mentor.support.create',compact('ticket_categories'));
    }
    public function mentorticketstore(Request $request)
    {
        $request->validate([
            'ticket_category_id' => 'required|integer',
            'ticket_description' => 'required|string',
            'attachment_url' => 'nullable|file|mimes:jpg,png,pdf|max:10240', // Max 10MB
        ]);

        $fileUrl = null;
        if ($request->hasFile('attachment_url')) {
            $file = $request->file('attachment_url');
            $filePath = 'ticketsattachments/' . uniqid() . '_' . $file->getClientOriginalName();

            // Upload to S3
            $uploaded = Storage::disk('s3')->put($filePath, file_get_contents($file));

            if ($uploaded) {
                // Construct the S3 URL based on the bucket's region and endpoint
                $bucket = env('AWS_BUCKET');
                $region = env('AWS_DEFAULT_REGION');
                $baseUrl = "https://{$bucket}.s3.{$region}.amazonaws.com/";
                $fileUrl = $baseUrl . $filePath;
            } else {
                return redirect()->back()->with('error', 'Failed to upload file to S3.');
            }
        }

        DB::table('ticket_descriptions')->insert([
            'ticket_category_id' => $request->ticket_category_id,
            'ticket_description' => $request->ticket_description,
            'user_id' => Auth::id(),
            'attachment_url' => $fileUrl, // Save S3 file URL in DB
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // return redirect()->back()->with('success', 'Ticket created successfully!');

        return redirect()->route('mentor.tickets')->with('success', 'Ticket created successfully!');

    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'response' => 'nullable|string|max:5000',
    //     ]);

    //     // Insert or update the response in ticket_responses
    //     $existingResponse = DB::table('ticket_responses')
    //         ->where('ticket_description_id', $id)
    //         ->whereNull('deleted_at')
    //         ->first();

    //     if ($existingResponse) {
    //         // Update existing response
    //         DB::table('ticket_responses')
    //             ->where('id', $existingResponse->id)
    //             ->update([
    //                 'ticket_response' => $request->response,
    //                 'updated_at' => now(),
    //             ]);
    //     } else {
    //         // Insert new response
    //         DB::table('ticket_responses')->insert([
    //             'ticket_description_id' => $id,
    //             'ticket_response' => $request->response,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);
    //     }

    //     return redirect()->route('mentor.tickets')->with('success', 'Response updated successfully.');
    // }


    // public function destroy($id)
    // {
    //     // Find the ticket
    //     $ticket = DB::table('ticket_descriptions')->where('id', $id)->first();

    //     if (!$ticket) {
    //         return redirect()->back()->with('error', 'Ticket not found.');
    //     }

    //     // If the ticket has an attachment, delete it from S3
    //     if ($ticket->attachment_url) {
    //         $filePath = str_replace("https://" . env('AWS_BUCKET') . ".s3." . env('AWS_DEFAULT_REGION') . ".amazonaws.com/", "", $ticket->attachment_url);
    //         Storage::disk('s3')->delete($filePath);
    //     }

    //     // Delete the ticket from the database
    //     DB::table('ticket_descriptions')->where('id', $id)->delete();

    //     return redirect()->back()->with('success', 'Ticket deleted successfully.');
    // }

    public function update(Request $request, $id)
    {
        if ($request->has('ticket_description')) {
            // 🔁 This is a mentor updating their own submitted ticket description

            $request->validate([
                'ticket_description' => 'required|string|max:1000',
            ]);

            DB::table('ticket_descriptions')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->update([
                    'ticket_description' => $request->ticket_description,
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Ticket updated successfully.');
        } elseif ($request->has('response')) {
            // 💬 This is a mentor responding to a mentee's ticket

            $request->validate([
                'response' => 'nullable|string|max:5000',
            ]);

            // Insert or update response
            $existingResponse = DB::table('ticket_responses')
                ->where('ticket_description_id', $id)
                ->whereNull('deleted_at')
                ->first();

            if ($existingResponse) {
                DB::table('ticket_responses')
                    ->where('id', $existingResponse->id)
                    ->update([
                        'ticket_response' => $request->response,
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('ticket_responses')->insert([
                    'ticket_description_id' => $id,
                    'ticket_response' => $request->response,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return redirect()->back()->with('success', 'Response updated successfully.');
        }

        return redirect()->back()->with('error', 'Invalid update request.');
    }
    public function destroy($id)
    {
        $ticket = DB::table('ticket_descriptions')->where('id', $id)->first();

        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket not found.');
        }

        // Delete file from S3 if present
        if ($ticket->attachment_url) {
            $filePath = str_replace(
                "https://" . env('AWS_BUCKET') . ".s3." . env('AWS_DEFAULT_REGION') . ".amazonaws.com/",
                "",
                $ticket->attachment_url
            );

            Storage::disk('s3')->delete($filePath);
        }

        // Soft delete (optional: use ->delete() if using Eloquent and SoftDeletes)
        DB::table('ticket_descriptions')->where('id', $id)->update([
            'deleted_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Ticket deleted successfully.');
    }

    
}

