<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\ModuleCompletedNotification;
use App\Mail\AdminModuleCompletedNotification;
use Illuminate\Support\Facades\Mail;
use App\Models\ModuleCompletionTracker; // Make sure the model is imported
use Carbon\Carbon; // For handling dates
use App\Mentor;
use App\AssignTask;
use App\Module;
use App\Session;
use App\Test;
use App\Subchapter;
use App\Moduleresourcebank;
use App\Chapter;
use App\Mapping;
class ModuleController extends Controller
{


    public function index()
    {
        // Retrieve modules and their related chapters including mentorsnote and quiz eligibility
        $modules = DB::table('modules')
            ->select('id', 'name', 'description', 'objective') // Include required fields
            ->whereNull('deleted_at')
            ->get();

        // For each module, retrieve its chapters
        foreach ($modules as $module) {
            $module->chapters = DB::table('chapters')
                ->select('id', 'chaptername', 'description', 'mentorsnote', 'objective')
                ->where('module_id', $module->id)
                ->whereNull('deleted_at')
                ->get();

        }

        // Pass the modules with chapters and quiz eligibility to the view
        return view('mentor.modules.index', compact('modules'));
    }




    public function showmentorchapters(Request $request)
    {
        // Retrieve the module ID and optional chapter ID from the request
        // $module_id = $request->query('module_id');
        $chapter_id = $request->query('chapter_id'); // Optional parameter for chapter
    
        // // Find the module
        // $module = Module::find($module_id);
    
        // if (!$module) {
        //     // If the module is not found, redirect back with an error message
        //     return redirect()->back()->with('error', 'Module not found.');
        // }

        // Retrieve the module ID from the request
    $module_id = $request->query('module_id');

    // Check if the module ID exists and retrieve the module
    $module = Module::find($module_id);

    if (!$module) {
        // If the module is not found, redirect back with an error message
        return redirect()->back()->with('error', 'Module not found.');
    }

    // Fetch all chapters related to the module
    $chapters = Chapter::where('module_id', $module_id)->get();

    if ($chapters->isEmpty()) {
        // If no chapters exist, handle gracefully
        return view('mentor.modules.mentorchapter', compact('chapters', 'module'))
            ->with('message', 'No chapters found for this module.');
    }
    
        // Fetch all chapters related to the module
        $chapters = Chapter::where('module_id', $module_id)->get();
    
        // Determine if each chapter has a quiz
        foreach ($chapters as $chapter) {
            $chapter->has_quiz = DB::table('questions')
                ->join('tests', 'questions.test_id', '=', 'tests.id')
                ->where('tests.chapter_id', $chapter->id)
                ->where('questions.mcq', 1)
                ->exists();
        }
    
        // Fetch the current chapter if chapter_id is provided
        $currentChapter = $chapter_id ? Chapter::find($chapter_id) : null;
    
        // Fetch the current subchapters for the chapter (if applicable)
        $subchapters = $currentChapter
            ? Subchapter::where('chapter_id', $currentChapter->id)->get()
            : null;
    
        // Return the view with all the required data
        return view('mentor.modules.mentorchapter', [
            'module' => $module,
            'chapters' => $chapters,
            'currentChapter' => $currentChapter,
            'subchapters' => $subchapters
        ]);
    }
    

    public function mentorsubchapter(Request $request)
    {

    	$chapter_id = $request->query('chapter_id');
    $current_subchapter_id = $request->query('chapter_id');

    // Get the current chapter using the chapter_id from the request
    $currentChapter = Chapter::find($chapter_id);

    // Get all subchapters of the chapter
    $subchapters = Subchapter::where('chapter_id', $chapter_id)->get();

    // Get the current subchapter
    $current_subchapter = Chapter::find($current_subchapter_id);

    // Find previous and next subchapters (optional)
    $previousSubchapter = ''; 
    $nextSubchapter = '';

    // Fetch module based on chapter_id (assuming a chapter belongs to one module)
    $module = Module::whereHas('chapters', function($query) use ($chapter_id) {
        $query->where('id', $chapter_id);
    })->first();

    // Get resources related to the module
    $moduleresources = Moduleresourcebank::where('chapterid_id', $chapter_id)->get();
                        
        return view('mentor.modules.mentorsubchapter', compact('subchapters', 'current_subchapter', 'previousSubchapter', 'nextSubchapter', 'moduleresources', 'module', 'currentChapter'));

    }



    public function menteemoduleprogress()
    {
        $mentorEmail = auth()->user()->email;

        $mentor = DB::table('mentors')->where('email', $mentorEmail)->first();
        if (!$mentor) {
            return redirect()->back()->with('error', 'Mentor not found.');
        }

        $mentorId = $mentor->id;

        // ✅ Get mapped mentee IDs for this mentor
        $menteeIds = DB::table('mappings')
            ->where('mentorname_id', $mentorId)
            ->pluck('menteename_id')
            ->toArray();

        // ✅ Get completed module IDs for all mapped mentees
        $moduleCompletionStatus = DB::table('module_completion_tracker')
            ->whereIn('mentee_id', $menteeIds)
            ->pluck('module_id')
            ->unique()
            ->toArray();

        $modules = DB::table('modules')->whereNull('deleted_at')->get();

        $progressData = [];
        foreach ($modules as $module) {
            $totalChapters = DB::table('chapters')->where('module_id', $module->id)->count();

            // Sum completions from all mentees
            $completedChapters = DB::table('module_completion_tracker')
                ->whereIn('mentee_id', $menteeIds)
                ->where('module_id', $module->id)
                ->count();

            $completionPercentage = ($totalChapters > 0)
                ? ($completedChapters / ($totalChapters * count($menteeIds))) * 100
                : 0;

            $progressData[] = [
                'module_name' => $module->name,
                'completion_percentage' => round($completionPercentage, 2),
            ];
        }

        $sessions = [];
        foreach ($modules as $module) {
            $session = DB::table('sessions')
                ->where('modulename_id', $module->id)
                ->whereIn('menteename_id', $menteeIds)
                ->get();

            $sessions[$module->id] = $session->isNotEmpty() ? $session : [];
        }

        return view('mentor.modules.menteemoduleprogress', compact('modules', 'sessions', 'moduleCompletionStatus', 'progressData'));
    }




public function markChapterCompletion($module_id)
{
    $user_id = auth()->user()->id; // Authenticated user ID
    $mentorEmail = auth()->user()->email;

    

    // Fetch the mentor and mentee details
    $mentor = DB::table('mentors')->where('email', $mentorEmail)->first();
    if (!$mentor) {
        return redirect()->back()->with('error', 'Mentor not found.');
    }

    $mapping = DB::table('mappings')->where('mentorname_id', $mentor->id)->first();
    if (!$mapping) {
        return redirect()->back()->with('error', 'No mentee mapped to this mentor.');
    }

    $mentee_Id = $mapping->menteename_id;

    // Validate module existence
    $module = DB::table('modules')->where('id', $module_id)->first();
    if (!$module) {
        return redirect()->back()->with('error', 'Invalid module ID.');
    }

    // Check and insert completion record
    $existingCompletion = DB::table('module_completion_tracker')
        ->where('mentee_id', $mentee_Id)
        ->where('module_id', $module_id)
        ->first();

    if (!$existingCompletion) {
        DB::table('module_completion_tracker')->insert([
            'user_id' => $user_id,
            'mentee_id' => $mentee_Id,
            'module_id' => $module_id,
            'completed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // Calculate module progress
    $modules = DB::table('modules')->whereNull('deleted_at')->get();
    $progressData = [];
    foreach ($modules as $module) {
        $totalChapters = DB::table('chapters')->where('module_id', $module->id)->count();
        $completedChapters = DB::table('module_completion_tracker')
            ->where('mentee_id', $mentee_Id)
            ->where('module_id', $module->id)
            ->count();

        $completionPercentage = ($totalChapters > 0) ? ($completedChapters / $totalChapters) * 100 : 0;

        $progressData[] = [
            'module_name' => $module->name,
            'completion_percentage' => round($completionPercentage, 2),
        ];
    }


     // Fetch module completion statuses
     $moduleCompletionStatus = DB::table('module_completion_tracker')
         ->where('mentee_id', $mentee_Id)
         ->pluck('module_id')
         ->toArray();

         $modules = DB::table('modules')->whereNull('deleted_at')->get();

        //  return $moduleCompletionStatus;

    return view('mentor.modules.menteemoduleprogress', compact('modules', 'moduleCompletionStatus', 'progressData'));
}


    
    public function moduleList()
    {
        $mentorEmail = Auth::user()->email;
        $mentor = DB::table('mentors')
        ->where('email', $mentorEmail)
        ->first();

        $sessions_list = DB::table('sessions')
        ->where('mentorname_id', $mentor->id)
        ->get();
        $totalSessions=$sessions_list->count();
        $totalMinutesMentored = 0;
        foreach ($sessions_list as $session) {
            if ($session->done === 'Yes') {
                $totalMinutesMentored += (int)$session->session_duration_minutes;
            }
        }
        $modules = DB::table('modules')->get();
        $sessions = [];
        foreach ($modules as $module) {
        // Check if any session exists for the module and mentee
        $session = DB::table('sessions')
            ->where('modulename_id', $module->id)
            ->where('mentorname_id', $mentor->id) // Check mentee ID
            ->get(); // Use get() to fetch multiple sessions
        // Add session(s) to sessions array
        if ($session->isNotEmpty()) {
            $sessions[$module->id] = $session;
        } else {
            $sessions[$module->id] = []; // Initialize as empty array if no sessions found
        }
          	// $modules=Module::all();

        return view('mentor.modules.moduleList',compact('modules','session','sessions_list','mentor'));
        }
    }
    
}