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
    $user = auth()->user();

    // Fetch mentor details using user_id
    $mentor = DB::table('mentors')->where('user_id', $user->id)->first();

    if (!$mentor) {
        return redirect()->back()->with('error', 'Mentor details not found.');
    }

    $certificateData = [
        'name' => $mentor->name,
        'date' => now()->format('F d, Y')
    ];

    return view('mentor.certificate.certificate', $certificateData);
}


    public function generateCertificate()
{
    $mentor = auth()->user();

    $mapping = DB::table('mappings')
                 ->where('mentor_email', $mentor->email)
                 ->first();

    if (!$mapping) {
        return redirect()->back()->with('error', 'No mapped mentee found for this mentor.');
    }

    $mentee = DB::table('mentees')
                ->where('id', $mapping->mentee_id)
                ->first();

    if (!$mentee) {
        return redirect()->back()->with('error', 'Mentee details not found.');
    }

    $course = DB::table('courses')
                ->where('mentee_id', $mentee->id)
                ->first();

    if (!$course) {
        return redirect()->back()->with('error', 'Course details not found.');
    }

    $certificateData = [
        'name' => $mentee->name,
        'course' => $course->title,
        'date' => now()->format('F d, Y')
    ];

    return view('mentor.certificate.certificate', $certificateData);
}


    // public function download()
    // {
    //     $name = 'John Doe'; // Replace with dynamic data
    //     $course = 'Laravel Basics'; // Replace with dynamic data
    //     $date = now()->format('F d, Y');
    
    //     // Render the certificate Blade view
    //     $html = view('mentor.certificate.certificate', compact('name', 'course', 'date'))->render();
    
    //     // Generate a PDF
    //     $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');
    
    //     // Download the generated PDF
    //     return $pdf->download('certificate.pdf');
    // }


   public function download()
    {
        $name = auth()->user()->name;
        $date = now()->format('F d, Y');

        $pdf = Pdf::loadView('mentor.certificate.certificate_only', compact('name', 'date'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('mentor_certificate.pdf');
    }
}
