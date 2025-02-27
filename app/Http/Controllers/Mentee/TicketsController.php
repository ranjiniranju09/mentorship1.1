<?php

namespace App\Http\Controllers\Mentee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TicketDescription;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TicketsController extends Controller
{
    public function menteetickets()
    {
        $categories = DB::table('ticketcategories')->select('id', 'category_description')->get();
        $userEmail = Auth::user()->email;

        $tickets = DB::table('ticket_descriptions')
            ->join('ticketcategories', 'ticket_descriptions.ticket_category_id', '=', 'ticketcategories.id')
            ->join('users', 'ticket_descriptions.user_id', '=', 'users.id')
            ->select(
                'ticket_descriptions.id',
                'users.email as user_email',
                'ticket_descriptions.ticket_description',
                'ticketcategories.category_description',
                'ticket_descriptions.status',
                'ticket_descriptions.file_path'
            )
            ->where('users.email', $userEmail)
            ->get();

        return view('mentee.tickets.index', compact('categories', 'userEmail', 'tickets'));
    }


    public function ticketstore(Request $request)
{
    // Validate input data
    $request->validate([
        'ticket_category_id' => 'required|integer',
        'ticket_description' => 'required|string',
        'attachment' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,zip,jpg,jpeg,png|max:20480',
    ]);

    // Get the logged-in user ID
    $userId = Auth::id();

     // Handle file upload
     $fileUrl = null;
     if ($request->hasFile('attachment')) {
         $file = $request->file('attachment');
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

    // Insert new ticket description using SQL Query Builder
    DB::table('ticket_descriptions')->insert([
        'ticket_category_id' => $request->input('ticket_category_id'),
        'ticket_description' => $request->input('ticket_description'),
        'user_id' => $userId,
        'file_path' => $fileUrl,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // return $fileUrl;
    // Redirect with success message
    return redirect()->route('mentee.tickets')->with('success', 'Ticket created successfully!');
}





    public function ticketshow($id)
    {
        $userId = auth()->id();

        // Retrieve the specific ticket details including file path
        $ticket = DB::table('ticket_descriptions')
            ->join('ticketcategories', 'ticket_descriptions.ticket_category_id', '=', 'ticketcategories.id')
            ->select(
                'ticket_descriptions.id',
                'ticket_descriptions.user_id',
                'ticket_descriptions.ticket_description',
                'ticketcategories.category_description',
                'ticket_descriptions.status',
                'ticket_descriptions.file_path' // Include file_path in the selection
            )
            ->where('ticket_descriptions.id', $id) // Ensure to fetch the specific ticket by ID
            ->where('ticket_descriptions.user_id', $userId) // Ensure the ticket belongs to the logged-in user
            ->first();

        // Retrieve all ticket categories (if needed)
        $categories = DB::table('ticketcategories')->select('id', 'category_description')->get();

        // Pass the ticket and categories to the view
        return view('mentee.tickets.index', compact('ticket', 'categories'));
    }

}
