<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class CertificateController extends Controller
{
    public function showmentorCertificate()
    {
        // Dummy data for testing; replace with actual database queries or logic as needed
        $certificateData = [
            'name' => 'John Doe', // Replace with actual mentee name
            'course' => 'Laravel Basics', // Replace with actual course name
            'date' => now()->format('F d, Y') // Current date
        ];

        return view('mentor.certificate.certificate', $certificateData);
    }

    public function generateCertificate()
    {
        // Get the logged-in mentor
        $mentor = auth()->user();
        
        // Fetch the mapped mentee details for the logged-in mentor
        $mapping = DB::table('mappings')
                     ->where('mentor_email', $mentor->email)
                     ->first();
        
        if (!$mapping) {
            return redirect()->back()->with('error', 'No mapped mentee found for this mentor.');
        }
        

        // Fetch the mentee details
        $mentee = DB::table('mentees')
                    ->where('id', $mapping->mentee_id)
                    ->first();

        if (!$mentee) {
            return redirect()->back()->with('error', 'Mentee details not found.');
        }

        // Fetch the course details (You may have a course table or hardcode it)
        $course = DB::table('courses')
                    ->where('mentee_id', $mentee->id)
                    ->first();

        if (!$course) {
            return redirect()->back()->with('error', 'Course details not found.');
        }

        // Generate certificate data
        $certificateData = [
            'name' => $mentee->name,
            'course' => $course->title,
            'date' => now()->format('F d, Y') // Format as desired
        ];

        // Pass the data to the view
        return view('certificate', $certificateData);
    }

    public function download()
    {
        $name = 'John Doe'; // Replace with dynamic data
        $course = 'Laravel Basics'; // Replace with dynamic data
        $date = now()->format('F d, Y');
    
        // Render the certificate Blade view
        $html = view('mentor.certificate.certificate', compact('name', 'course', 'date'))->render();
    
        // Generate a PDF
        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');
    
        // Download the generated PDF
        return $pdf->download('certificate.pdf');
    }
    
}
