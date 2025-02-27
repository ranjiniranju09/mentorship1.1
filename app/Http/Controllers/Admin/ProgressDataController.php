<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Exports\ModuleProgressExport;
use App\Exports\MasterSessionExport;
use App\Exports\ModuleReportExport;
use App\Exports\QuizCompletedReportExport;
use App\Exports\MentorwiseModuleReportExport;
use Maatwebsite\Excel\Facades\Excel; // Ensure this is also included
use App\Chapter;
use App\Module;
use App\Mentee;
use App\Models\ModuleCompletionTracker;


use Illuminate\Http\Request;  

class ProgressDataController extends Controller
{

    public function getModuleProgressData()
    {
        try {
            DB::beginTransaction();

            $moduleProgressQuery = DB::table('modules')
    ->join('module_completion_tracker', 'modules.id', '=', 'module_completion_tracker.module_id')
    ->join('mentees', 'module_completion_tracker.mentee_id', '=', 'mentees.id')
    ->join('mentors', 'module_completion_tracker.user_id', '=', 'mentors.user_id')
    // Join the sessions table to get total session duration for each mentor
    ->leftJoin('sessions', 'mentors.id', '=', 'sessions.mentorname_id')
    ->select(
        'modules.id as module_id',
        'modules.name as module_name',
        'mentees.name as mentee_name',
        'mentors.name as mentor_name',
        DB::raw('count(DISTINCT module_completion_tracker.module_id) as total_completed_modules'),
        DB::raw('(SELECT count(*) FROM modules WHERE modules.id NOT IN (SELECT module_id FROM module_completion_tracker WHERE mentee_id = module_completion_tracker.mentee_id)) as total_pending_modules'),
        DB::raw('(SELECT GROUP_CONCAT(modules.name) FROM modules JOIN module_completion_tracker ON modules.id = module_completion_tracker.module_id WHERE module_completion_tracker.mentee_id = mentees.id GROUP BY mentees.id) as completed_modules_names'),
        DB::raw('(SELECT GROUP_CONCAT(modules.name) FROM modules WHERE modules.id NOT IN (SELECT module_id FROM module_completion_tracker WHERE mentee_id = module_completion_tracker.mentee_id)) as pending_modules_names'),
        // Calculate total session time in hours for each mentor
        DB::raw('SUM(sessions.session_duration_minutes) / 60 as total_hours_engaged')
    )
    ->groupBy('modules.id', 'mentees.id', 'mentors.id');


            $moduleProgressData = $moduleProgressQuery->get();


                // return $moduleProgressQuery;
            DB::commit();

            return view('admin.progressData.masterdata', compact('moduleProgressData'));

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error fetching module progress data:', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to load module progress. Please try again.']);
        }
    }


    public function exportModuleProgress()
    {
        try {
            return Excel::download(new ModuleProgressExport, 'module_progress.xlsx');
        } catch (\Exception $e) {
            logger()->error('Error exporting module progress:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors([
                'error' => 'Failed to export module progress: ' . $e->getMessage()
            ]);
        }
    }
    


    
    // public function getModuleProgress() {
    //     // Fetch all modules
    //     $modules = Module::all();
    
    //     $moduleProgressData = [];
    
    //     foreach ($modules as $module) {
    //         // Get chapters for the current module
    //         $chapters = Chapter::where('module_id', $module->id)->get();
    
    //         // Count total mentees
    //         $totalMentees = Mentee::count();
    
    //         // Track completed and pending mentees
    //         $completedMentees = 0;
    
    //         foreach (Mentee::all() as $mentee) {
    //             $completedChapters = ModuleCompletionTracker::where('mentee_id', $mentee->id)
    //                 ->whereIn('chapter_id', $chapters->pluck('id'))
    //                 ->count();
    
    //             if ($completedChapters == $chapters->count()) {
    //                 $completedMentees++;
    //             }
    //         }
    
    //         $pendingMentees = $totalMentees - $completedMentees;
    
    //         // Add module data
    //         $moduleProgressData[] = [
    //             'module' => $module,
    //             'chapters' => $chapters,
    //             'completedMentees' => $completedMentees,
    //             'pendingMentees' => $pendingMentees,
    //         ];
    //     }
    
    //     return view('progress.module_progress', ['moduleProgressData' => $moduleProgressData]);
    // }
    
    public function modulereport() {
        
        try {

            // Start a transaction (optional, if updates are planned)
            DB::beginTransaction();
    
           // Fetch the progress data
                $progressData = DB::table('module_completion_tracker')
                ->join('modules', 'module_completion_tracker.module_id', '=', 'modules.id')
                ->join('mentees', 'module_completion_tracker.mentee_id', '=', 'mentees.id')
                ->join('mentors', 'module_completion_tracker.user_id', '=', 'mentors.user_id')
                ->select(
                    'mentees.name as mentee_name',
                    'mentors.name as mentor_name',
                    'modules.name as module_name',
                    'module_completion_tracker.completed_at'
                )
                ->orderBy('module_completion_tracker.completed_at', 'desc')
                ->get();

    
            // Commit the transaction
            DB::commit();
    
            // Return the view with progress data
            return view('admin.progressData.ModuleReport', compact('progressData'));
        } catch (\Exception $e) {
            // Roll back the transaction in case of error
            DB::rollBack();
    
            // Log or handle the error
            logger()->error('Error fetching module progress data:', ['error' => $e->getMessage()]);
    
            // Optionally redirect back with error message
            return redirect()->back()->withErrors(['error' => 'Failed to load module progress. Please try again.']);
        }
    }
    public function exportmodulereport()
    {
        try {
            return Excel::download(new ModuleReportExport, 'Overall_Module_report.xlsx');
        } catch (\Exception $e) {
            logger()->error('Error exporting module progress:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors([
                'error' => 'Failed to export module progress: ' . $e->getMessage()
            ]);
        }
    }

    public function mastersessionprogress() {

        $sessionProgressData = DB::table('mentors')
            ->leftJoin('module_completion_tracker', 'mentors.user_id', '=', 'module_completion_tracker.user_id')
            ->leftJoin('modules', 'module_completion_tracker.module_id', '=', 'modules.id')
            ->leftJoin('sessions', function ($join) {
                $join->on('mentors.id', '=', 'sessions.mentorname_id')
                    ->where('sessions.done', '=', 1); // Filter sessions where done = 1
            })
            ->select(
                'mentors.name as mentor_name',
                DB::raw('(SELECT GROUP_CONCAT(modules.name) 
                        FROM modules 
                        JOIN module_completion_tracker 
                        ON modules.id = module_completion_tracker.module_id 
                        WHERE module_completion_tracker.user_id = mentors.user_id) as completed_modules_names'),
                DB::raw('(SELECT GROUP_CONCAT(modules.name) 
                        FROM modules 
                        WHERE modules.id NOT IN 
                        (SELECT module_id 
                        FROM module_completion_tracker 
                        WHERE user_id = mentors.user_id)) as pending_modules_names'),
                DB::raw('COALESCE(SUM(sessions.session_duration_minutes) / 60, 0) as total_hours_engaged') // Aggregate only sessions where done = 1
            )
            ->groupBy('mentors.id')
            ->get();


        return view('admin.progressData.mastersessionreport', compact('sessionProgressData'));
    }

    public function exportSessionProgress()
    {
        try {
            return Excel::download(new MasterSessionExport, 'Master_Session_Report.xlsx');
        } catch (\Exception $e) {
            logger()->error('Error exporting module progress:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors([
                'error' => 'Failed to export module progress: ' . $e->getMessage()
            ]);
        }
    }
    public function mentorwiseModuleStatus()
{
    $sessionProgressData = DB::table('mentors')
        ->leftJoin('sessions', 'mentors.id', '=', 'sessions.mentorname_id')
        ->leftJoin('modules', 'sessions.modulename_id', '=', 'modules.id')
        ->select(
            'mentors.name as mentor_name',
            'modules.name as module_name', // Include module_name in the query
            DB::raw('COUNT(CASE WHEN sessions.done = 1 THEN 1 END) as completed_sessions_count'),
            DB::raw('COUNT(CASE WHEN sessions.done = 0 THEN 1 END) as pending_sessions_count')
        )
        ->groupBy('mentors.id', 'mentors.name', 'modules.name') // Group by module_name as well
        ->get();
    
    return view('admin.progressData.mentorwisesessionreport', compact('sessionProgressData'));
}

    
    public function exportMentorwiseModuleStatus()
    {
        try {
            return Excel::download(new MentorwiseModuleReportExport, 'Mentorwise_Module_Status_Report.xlsx');
        } catch (\Exception $e) {
            // Log the error with detailed information
            logger()->error('Error exporting mentor-wise module status:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Redirect back with an error message
            return redirect()->back()->withErrors([
                'error' => 'Failed to export mentor-wise module status: ' . $e->getMessage(),
            ]);
        }
    }
    public function quizCompletionReport()
    {
        
        try {

            $quizProgressData = DB::table('modules')
            ->leftJoin('quiz_results', 'modules.id', '=', 'quiz_results.module_id')
            ->leftJoin('questions', 'quiz_results.tests_id', '=', 'questions.test_id')
            ->leftJoin('mappings', function ($join) {
                $join->on('mappings.mentorname_id', '=', 'quiz_results.user_id')
                     ->orOn('mappings.menteename_id', '=', 'quiz_results.user_id');
            })
            ->leftJoin('mentors', 'mappings.mentorname_id', '=', 'mentors.id')
            ->leftJoin('mentees', 'mappings.menteename_id', '=', 'mentees.id')
            ->select(
                'modules.name as module_name',
                'mentors.name as mentor_name',
                'mentees.name as mentee_name',
                DB::raw('COUNT(DISTINCT questions.id) as total_questions'),
                DB::raw('COUNT(DISTINCT quiz_results.tests_id) as completed_quiz'), // Count of unique completed quizzes
                DB::raw('(1 - COUNT(DISTINCT quiz_results.tests_id)) as pending_quiz') // Assuming 1 test per module
            )
            ->where(function ($query) {
                $query->where('questions.mcq', 'yes')
                      ->orWhereNull('questions.id'); // Include modules without questions
            })
            ->groupBy('modules.id', 'modules.name', 'mentors.name', 'mentees.name')
            ->get();
        


        

        // return $quizProgressData;

           
            logger()->info('Quiz Progress Data:', ['data' => $quizProgressData]);

            return view('admin.progressData.QuizCompletedReport', compact('quizProgressData'));
        } catch (\Exception $e) {
            logger()->error('Error fetching quiz progress:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Failed to load quiz progress data.');
        }
    }


    

    public function exportQuizCompletedreport()
    {
        try {
            // Initiate the Excel download
            return Excel::download(new QuizCompletedReportExport, 'Quiz_Completed_Report_' . now()->format('Y-m-d_H-i-s') . '.xlsx');
        } catch (\Throwable $e) {
            // Log the error with detailed information
            logger()->error('Error exporting quiz completed report:', [
                'error_message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Redirect back with a user-friendly error message
            return redirect()->back()->withErrors([
                'error' => 'Failed to export quiz completed report. Please try again or contact support if the issue persists.',
            ]);
        }
    }



}