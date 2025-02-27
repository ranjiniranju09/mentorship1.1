<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{

   public function quizdetails()
{
    $mentor = auth()->user();

    // Get mentor ID based on email
    $mentorId = DB::table('mentors')->where('email', $mentor->email)->value('id');

    // Get the mapped mentee
    $mappedMentee = DB::table('mappings')
        ->where('mentorname_id', $mentorId)
        ->first();

    if (!$mappedMentee) {
        return redirect()->back()->with('error', 'No mentee found for this mentor.');
    }

    // Module-wise test counts
    $moduleWiseTestCounts = DB::table('tests')
        ->join('questions', 'tests.id', '=', 'questions.test_id')
        ->leftJoin('quiz_results', function ($join) use ($mappedMentee) {
            $join->on('tests.module_id', '=', 'quiz_results.module_id')
                ->on('quiz_results.tests_id', '=', 'tests.id')
                ->where('quiz_results.user_id', $mappedMentee->menteename_id); // Filter mentee-specific results
        })
        ->where('questions.mcq', 'Yes')
        ->whereNull('tests.deleted_at')
        ->whereNull('questions.deleted_at')
        ->select(
            'tests.module_id',
            DB::raw('COUNT(DISTINCT tests.id) as total_tests_with_mcq'),
            DB::raw('COUNT(DISTINCT CASE WHEN quiz_results.id IS NOT NULL THEN tests.id END) as completed_tests'),
            DB::raw('COUNT(DISTINCT CASE WHEN quiz_results.id IS NULL THEN tests.id END) as pending_tests')
        )
        ->groupBy('tests.module_id')
        ->get();

    // Fetch completed quizzes for the mapped mentee
    $completedQuizzes = DB::table('quiz_results')
        ->join('users', 'quiz_results.user_id', '=', 'users.id')
        ->join('mentees', 'users.email', '=', 'mentees.email')
        ->join('mappings', 'mentees.id', '=', 'mappings.menteename_id')
        ->join('modules', 'quiz_results.module_id', '=', 'modules.id')
        ->whereNull('modules.deleted_at')
        ->where('mappings.mentorname_id', $mentorId)
        ->where('mappings.menteename_id', $mappedMentee->menteename_id)
        ->whereNotNull('quiz_results.score')
        ->select('quiz_results.module_id', DB::raw('COUNT(quiz_results.id) as completed_count'))
        ->groupBy('quiz_results.module_id')
        ->get()
        ->keyBy('module_id');

    // Fetch module names and prepare output
    $moduleNames = DB::table('modules')->pluck('name', 'id');

    $modules = [];
    foreach ($moduleWiseTestCounts as $module) {
        $moduleId = $module->module_id;
        $completed = $completedQuizzes[$moduleId]->completed_count ?? 0;
        $pending = $module->total_tests_with_mcq - $completed;

        $modules[] = [
            'module_id' => $moduleId,
            'module_name' => $moduleNames[$moduleId] ?? 'Unknown Module',
            'completed_count' => $completed,
            'pending_count' => $pending,
        ];
    }

    // Calculate overall statistics
    $overallCompletedQuizzes = array_sum(array_column($modules, 'completed_count'));
    $overallPendingQuizzes = array_sum(array_column($modules, 'pending_count'));

    return view('mentor.quiz.quizdetails', [
        'modules' => $modules,
        'overallCompletedQuizzes' => $overallCompletedQuizzes,
        'overallPendingQuizzes' => $overallPendingQuizzes,
    ]);
}



public function calculateScore($moduleId, $menteeId)
{
    // Calculate the total score for quizzes in a module for a specific mentee
    $totalScore = DB::table('quiz_results')
        ->join('tests', 'quiz_results.tests_id', '=', 'tests.id') // Updated column name from test_id to tests_id
        ->where('tests.module_id', $moduleId)
        ->where('quiz_results.user_id', $menteeId)
        ->whereNotNull('quiz_results.score')
        ->sum('quiz_results.score');

    return $totalScore;
}

public function showmentorquiz(Request $request, $chapter_id)
{
    // Get the logged-in mentor
    $mentor = auth()->user();

    $chapterId = $chapter_id;

    // Fetch mentor ID based on the logged-in mentor's email
    $mentorDetails = DB::table('mentors')
        ->where('email', $mentor->email)
        ->first();

    if (!$mentorDetails) {
        return redirect()->back()->with('error', 'Mentor details not found.');
    }

    $mentorId = $mentorDetails->id;

    // Get the mapped mentee for the mentor
    $mappedMentee = DB::table('mappings')
        ->where('mentorname_id', $mentorId)
        ->whereNull('deleted_at') // Ensure mapping is not deleted
        ->first();

    if (!$mappedMentee) {
        return redirect()->back()->with('error', 'No mentee mapped to this mentor.');
    }

    // Get mentee details
    $mentee = DB::table('mentees')
        ->where('id', $mappedMentee->menteename_id)
        ->first();

    if (!$mentee) {
        return redirect()->back()->with('error', 'Mentee details not found.');
    }

    // Get the chapter details
    $chapter = DB::table('chapters')
        ->where('id', $chapter_id)
        ->first();

    if (!$chapter) {
        return redirect()->back()->with('error', 'Chapter not found.');
    }

    // Retrieve the module ID from the chapter
    $moduleId = $chapter->module_id;

    // Get the user ID linked to the mentee email
    $menteeUser = DB::table('users')
        ->where('email', $mentee->email)
        ->first();

    if (!$menteeUser) {
        return redirect()->back()->with('error', 'Mentee user account not found.');
    }

    $userId = $menteeUser->id;

    // Fetch the maximum quiz result for the mentee in the module
    // $maxResult = DB::table('quiz_results')
    //     ->where('user_id', $userId)
    //     ->where('module_id', $moduleId)
    //     ->orderBy('score', 'desc')
    //     ->first();

    $maxResult = DB::table('quiz_results')
    ->where('user_id', $userId)
    ->where('module_id', $moduleId)
    ->orderBy('score', 'desc')
    ->select('score', 'attempts') // Specify the columns you want to retrieve
    ->first();


    // Fetch discussion answers for the selected chapter and mapped mentee
    // $discussionAnswers = DB::table('discussion_answers')
    // ->join('questions', 'discussion_answers.question_id', '=', 'questions.id')
    // ->join('tests', 'questions.test_id', '=', 'tests.id') // Assuming questions link to tests
    // ->where('tests.chapter_id', $chapterId) // Use tests to filter by chapter
    // ->where('discussion_answers.menteename_id', $mappedMentee->menteename_id)
    // ->whereNull('discussion_answers.deleted_at') // Exclude soft-deleted answers
    // ->select('discussion_answers.*', 'questions.question_text')
    // ->get();

    // Get the discussion answers by joining discussion_answers, questions, and tests tables
    $discussionAnswers = DB::table('discussion_answers')
                            ->join('questions', 'discussion_answers.question_id', '=', 'questions.id')
                            ->join('tests', 'questions.test_id', '=', 'tests.id')  // Assuming questions link to tests
                            ->where('tests.chapter_id', $chapterId)  // Filter by chapter
                            ->where('discussion_answers.menteename_id', $mappedMentee->menteename_id)
                            ->whereNull('discussion_answers.deleted_at')  // Exclude soft-deleted answers
                            ->select('discussion_answers.*', 'questions.question_text')
                            ->get();

    // Get the chapter-wise quiz questions by joining tests and questions tables
    $chapterQuestions = DB::table('tests')
                          ->join('questions', 'tests.id', '=', 'questions.test_id') // Assuming questions link to tests
                          ->where('tests.chapter_id', $chapterId)
                          ->where('questions.mcq', 'no')  // Only retrieve non-MCQ questions
                          ->select('questions.*')
                          ->get();

    // Get the MCQ questions and their answers (where mcq = yes)
    $mcqQuestions = DB::table('tests')
    ->join('questions', 'tests.id', '=', 'questions.test_id') // Assuming questions link to tests
    ->leftJoin('quiz_results', 'questions.id', '=', 'quiz_results.tests_id')  // Join quiz result for answers
    ->where('tests.chapter_id', $chapterId)
    ->where('questions.mcq', 'yes')  // Only retrieve MCQ questions
    ->select('questions.*', 'quiz_results.score', 'quiz_results.attempts', 'quiz_results.total_points')
    ->get();


    // If no discussion answers exist, initialize an empty collection
    // if ($discussionAnswers->isEmpty()) {
    //     $discussionAnswers = collect();
    // }

    // Return the view with the necessary data
    return view('mentor.modules.quiz', compact(
        'mentor',
        'maxResult',
        'userId',
        'mappedMentee',
        'chapter',
        'mentee',
        'discussionAnswers',
        'chapterQuestions',
        'mcqQuestions'

    ));
}


    public function storeMentorReply(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'mentorsreply' => 'required|string|max:65535',
        ]);

        // Update the mentor's reply using Query Builder
        $updated = DB::table('discussion_answers')
            ->where('id', $id)
            ->update(['mentorsreply' => $request->input('mentorsreply'), 'updated_at' => now()]);

        // Check if the update was successful
        if ($updated) {
            return redirect()->back()->with('success', 'Reply submitted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to submit the reply. Please try again.');
        }
    }


}
