<?php

namespace App\Http\Controllers\Mentee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\QuizSubmittedNotification;
use App\Mail\QuizSubmittedToMentee;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Mentor;
use App\Mentee;
use App\AssignTask;
use Illuminate\Support\Facades\Redirect;
use App\Module;
use App\Chapter;
use App\Subchapter;
use App\Models\DiscussionAnswer;
use App\Test;
use App\Question;
use App\QuestionOption;
use App\QuizResult;
use App\Moduleresourcebank;
use App\Ticketcategory;
use App\TicketDescription;
use PhpParser\Node\Stmt\Return_;

class MenteeModuleController extends Controller
{
	public function index()
    {
        // Retrieve all modules
        $modules = Module::all();

        // Get the user ID of the authenticated user
        $userId = auth()->user()->id;

        // Fetch the max quiz result for each module for the user
        $maxResults = DB::table('quiz_results')
            ->select('module_id', DB::raw('MAX(score) as max_score'))
            ->where('user_id', $userId)
            ->groupBy('module_id')
            ->get()
            ->keyBy('module_id'); // Use module_id as the key for easy lookup

        return view('mentee.modules.index', compact('modules', 'maxResults'));
    }


    public function showchapters(Request $request)
{
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
        return view('mentee.modules.chapters', compact('chapters', 'module'))
            ->with('message', 'No chapters found for this module.');
    }

    // Attach MCQ check to each chapter
    $chapters->map(function ($chapter) use ($module_id) {
        // Check if the chapter has associated MCQs through the tests
        $chapter->has_mcq = Question::whereIn('test_id', function ($query) use ($chapter, $module_id) {
            $query->select('id')
                ->from('tests')
                ->where('chapter_id', $chapter->id)
                ->where('module_id', $module_id);  // Ensure the test is for the correct module
        })
        ->where('mcq', 'yes')
        ->exists();

        return $chapter;
    });

    // Get logged-in mentee email
    $loggedInEmail = Auth::user()->email;

    // Get mentee details
    $mentee = Mentee::where('email', $loggedInEmail)->first();

    // Get user ID using the logged-in mentee's email
    $user = DB::table('users')->where('email', $mentee->email)->first();
    $user_id = $user->id;

    // Pass the module, chapters, and other variables to the view
    return view('mentee.modules.chapters', compact('chapters', 'module', 'loggedInEmail', 'mentee', 'user'));
}

    public function subchaptercontent(Request $request)
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

    // Return view with necessary data
    return view('mentee.modules.subchapters', compact('subchapters', 'current_subchapter', 'previousSubchapter', 'nextSubchapter', 'moduleresources', 'module', 'currentChapter'));
}

    


    public function getDiscussionQuestions($chapterId)
{
    // Fetch the chapter details with an alias for 'chaptername' as 'title' for clarity
    $chapter = DB::table('chapters')
        ->select('id', 'chaptername as title', 'module_id') // Map 'chaptername' to 'title'
        ->where('id', $chapterId)
        ->first();

    // Fetch the chapter instance with related module
    $chapterModel = Chapter::findOrFail($chapterId);
    $module = $chapterModel->module; // Assuming the Chapter model has a relationship to Module

    // If the chapter does not exist, return a 404 error
    if (!$chapter) {
        abort(404, 'Chapter not found');
    }

    // Fetch discussion questions where mcq = 'no' for the chapter
    $discussionQuestions = DB::table('questions')
        ->join('tests', 'questions.test_id', '=', 'tests.id') // Join with tests table to filter by chapter_id
        ->leftJoin('discussion_answers', 'questions.id', '=', 'discussion_answers.question_id') // Include answers
        ->where('tests.chapter_id', $chapterId)
        ->where('questions.mcq', 'no') // Only non-MCQ questions
        ->select(
            'questions.id as question_id',
            'questions.question_text',
            'discussion_answers.discussion_answer',
            'discussion_answers.mentorsreply'
        ) // Select relevant fields
        ->orderBy('questions.id') // Order questions by ID (optional)
        ->get();

    // Pass chapter and discussion questions to the view
    return view('mentee.modules.viewdiscussion', [
        'chapter' => $chapter, // Chapter details
        'module' => $module,
        'discussionQuestions' => $discussionQuestions // Non-MCQ discussion questions with answers and replies
    ]);
}


public function discussionanswerstore(Request $request)
{
    $modules = Module::all();  // Get all modules
    
    $module_id = $request->input('module_id');
    $chapter_id = $request->query('chapter_id');

    // Validate the input
    $validatedData = $request->validate([
        'answers.*.discussion_answer' => 'required|string',
        'answers.*.question_id' => 'required|exists:questions,id',
    ]);

    // Fetch the logged-in user's email
    $loggedInEmail = Auth::user()->email;

    // Retrieve the menteename_id from the mentee table
    $mentee = DB::table('mentees')->where('email', $loggedInEmail)->first();

    if (!$mentee) {
        return redirect()->back()->withErrors(['error' => 'Mentee not found. Please ensure your account is correctly mapped.']);
    }

    $menteenameId = $mentee->id;

    foreach ($validatedData['answers'] as $answer) {
        $existingAnswer = DB::table('discussion_answers')
            ->where('question_id', $answer['question_id'])
            ->where('menteename_id', $menteenameId)
            ->first();

        if ($existingAnswer) {
            // Update existing answer
            DB::table('discussion_answers')
                ->where('id', $existingAnswer->id)
                ->update([
                    'discussion_answer' => $answer['discussion_answer'],
                    'updated_at' => now(),
                ]);
        } else {
            // Insert new answer
            DB::table('discussion_answers')->insert([
                'discussion_answer' => $answer['discussion_answer'],
                'question_id' => $answer['question_id'],
                'menteename_id' => $menteenameId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    // Fetch the module based on module_id
    $module = Module::find($module_id);
    if (!$module) {
        return redirect()->back()->withErrors(['error' => 'Module not found.']);
    }

    // Fetch the chapters related to the module
    $chapters = Chapter::where('module_id', $module_id)->get();

    // Pass both modules, module, and chapters to the view
    return view('mentee.modules.chapters', compact('modules', 'module', 'chapters'))
        ->with('success', 'Your answers have been submitted successfully!');
}

    

public function viewquiz(Request $request)
{
    $chapterId=$request->chapter_id;
    $chapter = Chapter::findOrFail($chapterId);

    $tests = Test::where('chapter_id', $chapterId)->with('questions.options')->get();
    //return $tests;
    return view('mentee.modules.viewquiz',compact('chapter','tests'));
}

public function submitQuiz(Request $request)
{
    // Validate incoming data
    $request->validate([
        'chapter_id' => 'required|exists:chapters,id',
        'module_id' => 'required|exists:modules,id',
        'test_id' => 'required|exists:tests,id',
    ]);

    $score = 0;
    $totalPoints = 0;

    // Retrieve chapter_id and module_id
    $chapterId = $request->input('chapter_id');
    $moduleId = $request->input('module_id');

    // Retrieve test_id
    $testId = $request->input('test_id');

    // Retrieve the tests_id for the module and chapter
    $test = \App\Test::where('module_id', $moduleId)
        ->where('chapter_id', $chapterId)
        ->first();

    if (!$test) {
        return redirect()->back()->with('error', 'No test found for the selected module and chapter.');
    }

    $testId = $test->id;

    // Calculate score
    foreach ($request->all() as $key => $value) {
        if (strpos($key, 'question_') === 0) {
            $questionId = str_replace('question_', '', $key);
            $selectedOptionId = $value;

            $question = Question::find($questionId);

            if (!$question) {
                return redirect()->back()->with('error', 'Question not found.');
            }

            $correctOption = $question->options()->where('is_correct', true)->first();

            if (!$correctOption) {
                return redirect()->back()->with('error', 'No correct option found for question ID: ' . $questionId);
            }

            $totalPoints += $question->points;

            if ($correctOption->id == $selectedOptionId) {
                $score += $question->points;
            }
        }
    }

    // Check if the mentee already has a record for this test
    $quizResult = QuizResult::where('user_id', auth()->user()->id)
        ->where('tests_id', $testId)
        ->first();

    if ($quizResult) {
        // If record exists, update it
        $quizResult->update([
            'score' => $score,
            'attempts' => $quizResult->attempts + 1, // Increment attempts
            'total_points' => $totalPoints,
        ]);
    } else {
        // Otherwise, create a new entry
        $quizResult = QuizResult::create([
            'user_id' => auth()->user()->id,
            'module_id' => $moduleId,
            'tests_id' => $testId,
            'score' => $score,
            'attempts' => 1,
            'total_points' => $totalPoints,
        ]);
    }

    // Fetch mapping data
    $result = DB::table('mappings')
        ->join('mentees', 'mappings.menteename_id', '=', 'mentees.id')
        ->join('mentors', 'mappings.mentorname_id', '=', 'mentors.id')
        ->select(
            'mentees.email as mentee_email',
            'mentees.id as mentee_id',
            'mentees.name as mentee_name',
            'mentors.email as mentor_email',
            'mentors.name as mentor_name',
            'mappings.mentorname_id'
        )
        ->where('mentees.email', auth()->user()->email)
        ->first();

    if (!$result) {
        return redirect()->back()->with('error', 'No mentor found for this mentee!');
    }

    $mentor = \App\Mentor::find($result->mentorname_id);

    // Send notifications
    Mail::to($result->mentor_email)->send(new QuizSubmittedNotification($result, $mentor, $quizResult));
    Mail::to($result->mentee_email)->send(new QuizSubmittedToMentee($result, $score));

    // Redirect back with success message
    return redirect()
        ->route('menteesessions.index')
        ->with('success', 'Quiz submitted successfully! Your score is: ' . $score);
}


    public function menteetickets()
{
    $tickets = DB::table('ticket_descriptions')
        ->join('ticketcategories', 'ticket_descriptions.ticket_category_id', '=', 'ticketcategories.id')
        ->select(
            'ticket_descriptions.id',
            'ticket_descriptions.ticket_title',
            'ticket_descriptions.ticket_description',
            'ticket_descriptions.created_at',
            'ticket_descriptions.attachment_url', // Include this column
            'ticket_descriptions.response', // ✅ Add this column
            'ticketcategories.category_description as category'
        )
        ->where('ticket_descriptions.user_id', Auth::id())
        ->whereNull('ticket_descriptions.deleted_at')
        ->get();


    return view('mentee.tickets.index', compact('tickets'));
}
    public function ticketscreate()
    {
        //$ticket_categories=Ticketcategory::all();
        $ticket_categories = Ticketcategory::pluck('category_description', 'id');
        return view('mentee.tickets.create',compact('ticket_categories'));
    }
    public function ticketstore(Request $request)
{
    $request->validate([
        'ticket_category_id' => 'required|integer',
        'ticket_description' => 'required|string',
        'attachment_url' => 'nullable|file|mimes:jpg,png,pdf|max:2048', // Only JPG, PNG, and PDF files, max 2MB
    ]);

    $fileUrl = null;
    if ($request->hasFile('attachment_url')) {
        $file = $request->file('attachment_url');
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

    DB::table('ticket_descriptions')->insert([
        'ticket_category_id' => $request->ticket_category_id,
        'ticket_description' => $request->ticket_description,
        'user_id' => Auth::id(),
        'attachment_url' => $fileUrl, // Save S3 file URL in DB
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // return redirect()->back()->with('success', 'Ticket created successfully!');

    return redirect()->route('mentee.tickets')->with('success', 'Ticket created successfully!');

}

public function destroy($id)
{
    // Find the ticket
    $ticket = DB::table('ticket_descriptions')->where('id', $id)->first();

    if (!$ticket) {
        return redirect()->back()->with('error', 'Ticket not found.');
    }

    // If the ticket has an attachment, delete it from S3
    if ($ticket->attachment_url) {
        $filePath = str_replace("https://" . env('AWS_BUCKET') . ".s3." . env('AWS_DEFAULT_REGION') . ".amazonaws.com/", "", $ticket->attachment_url);
        Storage::disk('s3')->delete($filePath);
    }

    // Delete the ticket from the database
    DB::table('ticket_descriptions')->where('id', $id)->delete();

    return redirect()->back()->with('success', 'Ticket deleted successfully.');
}


    // public function ticketstore(Request $request)
    // {
    //     //return $request;
    //     $ticketDescription = new TicketDescription();
    //     $ticketDescription->ticket_category_id = $request->ticket_category_id;
    //     $ticketDescription->ticket_description = $request->ticket_description;
    //     $ticketDescription->user_id = $request->user_id;
    //     $ticketDescription->save();
    //     //return redirect()->back()->with('success', 'Ticket created successfully!');
    //     return redirect()->route('mentee.tickets')->with('success', 'Ticket created successfully!');


    // }
    
}