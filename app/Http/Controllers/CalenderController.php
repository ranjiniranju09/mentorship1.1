<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;



class CalenderController extends Controller
{
    public function showCalendar()
    {
        // Get the mentee ID based on the logged-in user's email
        $menteeId = DB::table('mentees')
            ->where('email', auth()->user()->email)
            ->value('id'); // Retrieve the id (menteename_id) of the mentee
    
        // Now use the mentee ID to get the sessions
        $calendarSessions = DB::table('sessions')
            ->where('menteename_id', $menteeId) // Use the retrieved mentee ID
            ->get(['session_title', 'sessiondatetime', 'sessionlink'])
            ->map(function ($session) {
                return [
                    'title' => $session->session_title,
                    'start' => $session->sessiondatetime,
                    'url' => $session->sessionlink,
                ];
            });
    
        // Pass the sessions data to the view
        return view('mentee.calender.index', ['calendarSessions' => $calendarSessions]);
    }
    public function showMentorCalendar()
    {
        // Get the mentee ID based on the logged-in user's email
        $mentorId = DB::table('mentors')
            ->where('email', auth()->user()->email)
            ->value('id'); // Retrieve the id (menteename_id) of the mentee
    
        // Now use the mentee ID to get the sessions
        $calendarSessions = DB::table('sessions')
            ->where('mentorname_id', $mentorId) // Use the retrieved mentee ID
            ->get(['session_title', 'sessiondatetime', 'sessionlink'])
            ->map(function ($session) {
                return [
                    'title' => $session->session_title,
                    'start' => $session->sessiondatetime,
                    'url' => $session->sessionlink,
                ];
            });
    
        // Pass the sessions data to the view
        return view('mentor.calender.index', ['calendarSessions' => $calendarSessions]);
    }
    
    
}



