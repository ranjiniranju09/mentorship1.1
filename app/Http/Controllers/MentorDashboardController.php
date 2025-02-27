<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class MentorDashboardController extends Controller
{
    //
   
    public function index()
{
    $mentorEmail = Auth::user()->email;

    // Fetch the mentor's details
    $mentor = DB::table('mentors')
        ->where('email', $mentorEmail)
        ->first();
    
    // Retrieve the mapped mentee information for the logged-in mentor
    $mappedMentee = DB::table('mappings')
        ->where('mentorname_id', $mentor->id)
        ->whereNull('deleted_at')
        ->first();
    
    // Fetch mentee details using menteename_id from the mapped table if a mapping exists
    $menteeDetails = null;
    if ($mappedMentee) {
        $menteeDetails = DB::table('mentees')
            ->where('id', $mappedMentee->menteename_id)
            ->first();
    }

    $sessions_list = DB::table('sessions')
        ->where('mentorname_id', $mentor->id)
        ->get();

    $totalSessions = $sessions_list->count();

    // $totalMinutesMentored = 0;
    // foreach ($sessions_list as $session) {
    //     if ($session->done === 'Yes') {
    //         $totalMinutesMentored += (int)$session->session_duration_minutes;
    //     }
    // }

    $totalMinutes = DB::table('sessions')
    ->where('done', 1)
    ->sum('session_duration_minutes');


    $modules = DB::table('modules')
        ->whereNull('deleted_at')
        ->get();

    $sessions = [];
    foreach ($modules as $module) {
        $session = DB::table('sessions')
            ->where('modulename_id', $module->id)
            ->where('mentorname_id', $mentor->id)
            ->get();

        $sessions[$module->id] = $session->isNotEmpty() ? $session : [];
    }

    return view('mentor.dashboard', compact(
        'sessions_list',
        'totalSessions',
        'totalMinutes',
        'sessions',
        'modules',
        'menteeDetails' // Pass mentee details to the view
    ));
}


    public function markAsDone($id) {
    DB::table('sessions')
        ->where('id', $id)
        ->update(['done' => 'Yes']);
    
    // Redirect back or return a response
            //return Redirect::back()->with('success', 'Session marked as Completed successfully.');
            return redirect()->back()->with('success', 'Session marked as completed successfully.');


    }
        
}
