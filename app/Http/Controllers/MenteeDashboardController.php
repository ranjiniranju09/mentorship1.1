<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Chapter;

class MenteeDashboardController extends Controller
{
    /*
    public function index()
    {
        $menteeEmail = auth()->user()->email;

        $mentee = DB::table('mentees')->where('email', $menteeEmail)->first();

         if ($mentee) {
            // Get the ID of the mentee
            $menteeId = $mentee->id;

            $mentor = DB::table('mentors')
            ->join('mappings', 'mentors.id', '=', 'mappings.mentorname_id')
            ->where('mappings.menteename_id', $menteeId)
            ->first();

            $mentorName = $mentor ? $mentor->name : null;
        }else{
            $mentorName = null;
        }
        $modules = DB::table('modules')->get();
        $sessions = [];
        
        foreach ($modules as $module) {
        // Check if any session exists for the module and mentee
        $session = DB::table('sessions')
            ->where('modulename_id', $module->id)
            ->where('menteename_id', $menteeId) // Check mentee ID
            ->get(); // Use get() to fetch multiple sessions

        // Add session(s) to sessions array
        if ($session->isNotEmpty()) {
            $sessions[$module->id] = $session;
        } else {
            $sessions[$module->id] = []; // Initialize as empty array if no sessions found
        }
    }
        
        
         $tasks = DB::table('assign_tasks')
        ->where('mentee_id', $menteeId)
        ->where('mentor_id', $mentor->id)
        ->get();
        

        return view('mentee.dashboard',compact('mentorName','modules','sessions','tasks'));
    }*/
    public function index()
    {
        // Fetch the current mentee's ID
        //$menteeId = auth()->user()->id;
        $menteeEmail = auth()->user()->email;

        $mentee = DB::table('mentees')->where('email', $menteeEmail)->first();
        
        

        $menteeId=$mentee->id;
        
        // Fetch the mentor assigned to the mentee
        // $mentor = DB::table('mentors')
        //     ->join('mappings', 'mentors.id', '=', 'mappings.mentorname_id')
        //     ->where('mappings.menteename_id', $menteeId)
        //     ->first();

        $mentor = DB::table('mentors')
        ->join('mappings', 'mentors.id', '=', 'mappings.mentorname_id')
        ->where('mappings.menteename_id', $menteeId)
        ->whereNull('mappings.deleted_at')
        ->first();


        $mentorName = $mentor ? $mentor->name : null;
        $mentorMobile = $mentor ? $mentor->mobile : null;
        $mentorEmail = $mentor ? $mentor->email : null;


        // Fetch all modules
        $modules = DB::table('modules')->get();
        $progressData = [];

        foreach ($modules as $module) {
            $totalChapters = DB::table('chapters')->where('module_id', $module->id)->count();

            $completedChapters = DB::table('module_completion_tracker')
                                    ->where('mentee_id', $menteeId)
                                    ->where('module_id', $module->id)
                                    ->count();

            $completionPercentage = ($totalChapters > 0) ? ($completedChapters / $totalChapters) * 100 : 0;

            $progressData[] = [
                'module_name' => $module->name,
                'completion_percentage' => round($completionPercentage, 2)
            ];
        }

        // Fetch sessions for each module
        $sessions = [];
        foreach ($modules as $module) {
            
            $session = DB::table('sessions')
                ->where('modulename_id', $module->id)
                ->where('menteename_id', $menteeId)
                ->get();


            if ($session->isNotEmpty()) {
                $sessions[$module->id] = $session;
            } else {
                $sessions[$module->id] = [];
            }

        }


        // Fetch tasks assigned to the mentee by the mentor
        $tasks = DB::table('assign_tasks')
            ->where('mentee_id', $menteeId)
            ->where('mentor_id', optional($mentor)->id) // Use optional() to avoid error if mentor is null
            ->get();

        // Fetch guest lectures for the mentee
        $guestLectures = DB::table('guest_lecture_mentee')
            ->join('guest_lectures', 'guest_lecture_mentee.guest_lecture_id', '=', 'guest_lectures.id')
            ->where('guest_lecture_mentee.mentee_id', $menteeId)
            ->get();
        
        return view('mentee.dashboard', compact('mentorName','mentorMobile','mentorEmail', 'modules', 'sessions', 'tasks', 'guestLectures','progressData'));
    }

}
