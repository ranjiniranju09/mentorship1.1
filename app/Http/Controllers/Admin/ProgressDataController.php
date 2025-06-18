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

    public function getModuleProgressData() // overallmoduleprogress
    {
        try {
            DB::beginTransaction();

            $moduleProgressQuery = DB::table('mentees')
                // Only include mapped mentees
                ->join('mappings', 'mentees.id', '=', 'mappings.menteename_id')

                // Join mentors using mapping
                ->join('mentors', 'mappings.mentorname_id', '=', 'mentors.id')

                // Join module completion tracker
                ->leftJoin('module_completion_tracker', 'mentees.id', '=', 'module_completion_tracker.mentee_id')

                // Join sessions for the mapped mentor (still can be left join in case sessions not done yet)
                ->leftJoin('sessions', function($join) {
                    $join->on('mentors.id', '=', 'sessions.mentorname_id')
                        ->where('sessions.done', '=', 1);
                })

                ->select(
                    'mentees.id as mentee_id',
                    'mentees.name as mentee_name',
                    'mentors.name as mentor_name',

                    // Count of completed modules
                    DB::raw('COUNT(DISTINCT module_completion_tracker.module_id) as total_completed_modules'),

                    // Pending modules = total - completed
                    DB::raw('(SELECT COUNT(*) FROM modules) - COUNT(DISTINCT module_completion_tracker.module_id) as total_pending_modules'),

                    // Completed module names
                    DB::raw('(
                        SELECT GROUP_CONCAT(DISTINCT m.name)
                        FROM modules m
                        JOIN module_completion_tracker mct ON m.id = mct.module_id
                        WHERE mct.mentee_id = mentees.id
                    ) as completed_modules_names'),

                    // Pending module names
                    DB::raw('(
                        SELECT GROUP_CONCAT(name)
                        FROM modules
                        WHERE id NOT IN (
                            SELECT module_id 
                            FROM module_completion_tracker 
                            WHERE mentee_id = mentees.id
                        )
                    ) as pending_modules_names'),

                    // Total mentor session time in hours
                    DB::raw('SUM(sessions.session_duration_minutes) / 60 as total_hours_engaged')
                )

                ->groupBy('mentees.id', 'mentees.name', 'mentors.name');

            $moduleProgressData = $moduleProgressQuery->get();

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
    // Only include mentors that are mapped to at least one mentee
    ->join('mappings', 'mentors.id', '=', 'mappings.mentorname_id')

    // Optional: join mentees (if you want to group mentee data too)
    ->join('mentees', 'mappings.menteename_id', '=', 'mentees.id')

    // Join module completion tracker by mentee
    ->leftJoin('module_completion_tracker', 'mentees.id', '=', 'module_completion_tracker.mentee_id')

    // Join modules
    ->leftJoin('modules', 'module_completion_tracker.module_id', '=', 'modules.id')

    // Join sessions where session is done
    ->leftJoin('sessions', function ($join) {
        $join->on('mentors.id', '=', 'sessions.mentorname_id')
             ->where('sessions.done', '=', 1);
    })

    ->select(
        'mentors.name as mentor_name',

        // Completed modules by mapped mentees
        DB::raw('(
            SELECT GROUP_CONCAT(DISTINCT m.name)
            FROM modules m
            JOIN module_completion_tracker mct ON m.id = mct.module_id
            WHERE mct.mentee_id IN (
                SELECT menteename_id
                FROM mappings
                WHERE mentorname_id = mentors.id
            )
        ) as completed_modules_names'),

        // Pending modules (i.e., all modules not yet completed by mapped mentees)
        DB::raw('(
            SELECT GROUP_CONCAT(name)
            FROM modules
            WHERE id NOT IN (
                SELECT module_id
                FROM module_completion_tracker
                WHERE mentee_id IN (
                    SELECT menteename_id
                    FROM mappings
                    WHERE mentorname_id = mentors.id
                )
            )
        ) as pending_modules_names'),

        // Total session time for each mapped mentor
        DB::raw('COALESCE(SUM(sessions.session_duration_minutes) / 60, 0) as total_hours_engaged')
    )

    ->groupBy('mentors.id', 'mentors.name')
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
        // Only include mentors who are mapped
        ->join('mappings', 'mentors.id', '=', 'mappings.mentorname_id')

        // Join sessions and modules
        ->leftJoin('sessions', 'mentors.id', '=', 'sessions.mentorname_id')
        ->leftJoin('modules', 'sessions.modulename_id', '=', 'modules.id')

        ->select(
            'mentors.name as mentor_name',
            'modules.name as module_name', // Include module name
            DB::raw('COUNT(CASE WHEN sessions.done = 1 THEN 1 END) as completed_sessions_count'),
            DB::raw('COUNT(CASE WHEN sessions.done = 0 THEN 1 END) as pending_sessions_count')
        )

        ->groupBy('mentors.id', 'mentors.name', 'modules.name')
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

            //with total score of all the modules 
        //    $quizProgressData = DB::select("
        //     SELECT 
        //         mentees.id AS mentee_id,
        //         mentees.name AS mentee_name,

        //         -- Total modules
        //         (SELECT COUNT(*) FROM modules) AS total_modules,

        //         -- Completed modules count
        //         COUNT(DISTINCT quiz_results.module_id) AS completed_modules,

        //         -- Pending modules count
        //         ((SELECT COUNT(*) FROM modules) - COUNT(DISTINCT quiz_results.module_id)) AS pending_modules,

        //         -- Completed module names
        //         GROUP_CONCAT(DISTINCT completed_modules.name SEPARATOR ', ') AS completed_module_names,

        //         -- Pending module names
        //         (
        //             SELECT GROUP_CONCAT(DISTINCT m.name SEPARATOR ', ')
        //             FROM modules m
        //             WHERE m.id NOT IN (
        //                 SELECT module_id
        //                 FROM quiz_results
        //                 WHERE user_id = mentees.user_id
        //             )
        //         ) AS pending_module_names,

        //         -- Total score and attempts
        //         COALESCE(SUM(quiz_results.score), 0) AS total_score,
        //         COALESCE(SUM(quiz_results.attempts), 0) AS total_attempts

        //     FROM mentees

        //     -- Only mapped mentees
        //     LEFT JOIN mappings ON mappings.menteename_id = mentees.id

        //     -- Join quiz results for this mentee
        //     LEFT JOIN quiz_results ON quiz_results.user_id = mentees.user_id

        //     -- Join completed modules to get their names
        //     LEFT JOIN modules AS completed_modules ON completed_modules.id = quiz_results.module_id

        //     GROUP BY mentees.id, mentees.name, mentees.user_id
        //     ORDER BY mentees.name
        // ");


        //with score for each modules 
        $quizProgressData = DB::select("
    SELECT 
        mentees.id AS mentee_id,
        mentees.name AS mentee_name,
        mentees.user_id AS mentee_user_id,

        (SELECT COUNT(*) FROM modules) AS total_modules,
        COUNT(DISTINCT quiz_results.module_id) AS completed_modules,
        ((SELECT COUNT(*) FROM modules) - COUNT(DISTINCT quiz_results.module_id)) AS pending_modules,

        GROUP_CONCAT(DISTINCT CONCAT(completed_modules.name, ' (Score: ', quiz_results.score, ')') SEPARATOR ', ') AS completed_module_scores,

        (
            SELECT GROUP_CONCAT(DISTINCT m.name SEPARATOR ', ')
            FROM modules m
            WHERE m.id NOT IN (
                SELECT module_id
                FROM quiz_results
                WHERE user_id = mentees.user_id
            )
        ) AS pending_module_names,

        COALESCE(SUM(quiz_results.attempts), 0) AS total_attempts

    FROM mentees
    LEFT JOIN mappings ON mappings.menteename_id = mentees.id
    LEFT JOIN quiz_results ON quiz_results.user_id = mentees.user_id
    LEFT JOIN modules AS completed_modules ON completed_modules.id = quiz_results.module_id

    GROUP BY mentees.id, mentees.name, mentees.user_id
    ORDER BY mentees.name
");



            // Log the fetched data for debugging
            // logger()->info('Quiz Progress Data:', ['data' => $quizProgressData]);

            // Return the view with the quiz progress data



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
            return Excel::download(new QuizCompletedReportExport, 'Quiz_Completed_Report.xlsx');
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