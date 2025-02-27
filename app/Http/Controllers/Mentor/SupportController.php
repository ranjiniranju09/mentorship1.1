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
    public function mentortickets()
{
    $tickets = DB::table('ticket_descriptions')
        ->join('ticketcategories', 'ticket_descriptions.ticket_category_id', '=', 'ticketcategories.id')
        ->select(
            'ticket_descriptions.id',
            'ticket_descriptions.ticket_title',
            'ticket_descriptions.ticket_description',
            'ticket_descriptions.created_at',
            'ticket_descriptions.attachment_url', // Include this column
            'ticket_descriptions.response', // ✅ Add this column
            'ticketcategories.category_description as category'
        )
        ->where('ticket_descriptions.user_id', Auth::id())
        ->whereNull('ticket_descriptions.deleted_at')
        ->get();

    return view('mentor.support.index', compact('tickets'));
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

    // public function mentorticketstore(Request $request)
    // {
    //     //return $request;
    //     $ticketDescription = new TicketDescription();
    //     $ticketDescription->ticket_category_id = $request->ticket_category_id;
    //     $ticketDescription->ticket_description = $request->ticket_description;
    //     $ticketDescription->user_id = $request->user_id;
    //     $ticketDescription->save();
    //     //return redirect()->back()->with('success', 'Ticket created successfully!');
    //     return redirect()->route('mentor.tickets')->with('success', 'Ticket created successfully!');


    public function destroy($id)
{
    // Find the ticket
    $ticket = DB::table('ticket_descriptions')->where('id', $id)->first();

    if (!$ticket) {
        return redirect()->back()->with('error', 'Ticket not found.');
    }

    // If the ticket has an attachment, delete it from S3
    if ($ticket->attachment_url) {
        $filePath = str_replace("https://" . env('AWS_BUCKET') . ".s3." . env('AWS_DEFAULT_REGION') . ".amazonaws.com/", "", $ticket->attachment_url);
        Storage::disk('s3')->delete($filePath);
    }

    // Delete the ticket from the database
    DB::table('ticket_descriptions')->where('id', $id)->delete();

    return redirect()->back()->with('success', 'Ticket deleted successfully.');
}
    
}

