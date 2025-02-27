<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiscussionAnswer; // Model for discussion answers
use App\Question; // Model for questions

class DiscussionController extends Controller
{
    /**
     * Display a listing of the discussions.
     */
    public function index()
    {
        // Fetch all discussion answers with their related question
        // $discussions = DiscussionAnswer::with('question')->get();

        return view('admin.discussionanswers.index');
    }

    /**
     * Show the form for creating a new discussion answer.
     */
    // public function create()
    // {
    //     // $questions = Question::all(); // Fetch questions for the dropdown
    //     return view('admin.discussions.create', compact('questions'));
    // }
    public function create()
    {
        // Fetch questions from the database
        $questions = Question::select('id', 'question_text')->pluck('question_text', 'id');

        return view('admin.discussionanswers.create', compact('questions'));
    }

    /**
     * Store a newly created discussion answer in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'discussion_answer' => 'required|string|max:255',
        ]);

        DiscussionAnswer::create([
            'question_id' => $request->question_id,
            'discussion_answer' => $request->discussion_answer,
        ]);

        return view('admin.discussionanswers.create');

        // return redirect()->route('admin.discussionanswers.index')->with('success', 'Discussion answer added successfully.');
    }


    /**
     * Show the form for editing the specified discussion answer.
     */
    public function edit($id)
    {
        // $discussion = DiscussionAnswer::findOrFail($id);
        // $questions = Question::all();

        return view('admin.discussions.edit', compact('discussion', 'questions'));
    }

    /**
     * Update the specified discussion answer in storage.
     */
    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'question_id' => 'required|exists:questions,id',
        //     'answer' => 'required|string|max:255',
        // ]);

        // $discussion = DiscussionAnswer::findOrFail($id);
        // $discussion->update([
        //     'question_id' => $request->question_id,
        //     'answer' => $request->answer,
        // ]);

        return redirect()->route('admin.discussions.index')->with('success', 'Discussion answer updated successfully.');
    }

    /**
     * Remove the specified discussion answer from storage.
     */
    public function destroy($id)
    {
        // $discussion = DiscussionAnswer::findOrFail($id);
        // $discussion->delete();

        return redirect()->route('admin.discussions.index')->with('success', 'Discussion answer deleted successfully.');
    }
}
