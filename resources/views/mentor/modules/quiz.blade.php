@extends('layouts.mentor')

@section('content')
<div class="container mt-5">

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2>{{ $chapter->chaptername }} - Quiz</h2>
                </div>
                <div class="card-body">
                    <p>Answer the following questions based on the chapter content.</p>
                    
                    <!-- Mentee Quiz Summary Section -->
                    <div class="quiz-summary mb-5">
                        <h4>Mentee Quiz Details</h4>
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Mentee Name:</strong> {{ $mentee->name }}</li>
                            <li class="list-group-item"><strong>Score:</strong> {{$maxResult->score ?? 'N/A' }}</li>
                            <li class="list-group-item"><strong>Chapter Name:</strong> {{ $chapter->chaptername ?? 'N/A' }} </li>
                        </ul>
                    </div>

                    <!-- Discussion Questions Section -->
                    <div class="discussion-questions mb-5">
                        <h4>Discussion Points</h4>
                        @if($discussionAnswers->isEmpty())
                            <p>No discussion answers available for this chapter.</p>
                        @else
                            <ul class="list-group">
                                @foreach($discussionAnswers as $answer)
                                    <li class="list-group-item">
                                        <p><strong>Question:</strong> {{ $answer->question_text }}</p>
                                        <p><strong>Answer:</strong> {{ $answer->discussion_answer }}</p>

                                        <!-- Reply Button -->
                                        <button class="btn btn-warning btn-sm mt-2" 
                                            onclick="toggleReplyForm({{ $answer->id }})">
                                            Reply
                                        </button>

                                        <!-- Mentor's reply form (Initially Hidden) -->
                                        <form 
                                            action="{{ route('discussionanswers.reply', $answer->id) }}" 
                                            method="POST" 
                                            id="replyForm_{{ $answer->id }}" 
                                            style="display: none;">
                                            @csrf
                                            <div class="form-group mt-3">
                                                <label for="mentorsreply_{{ $answer->id }}">Mentor's Reply</label>
                                                <textarea 
                                                    name="mentorsreply" 
                                                    id="mentorsreply_{{ $answer->id }}" 
                                                    class="form-control" 
                                                    rows="3"
                                                >{{ $answer->mentorsreply }}</textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-2">Submit Reply</button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <!-- MCQ Questions Section -->
                    {{--<div class="mcq-quiz mb-5">
                        <h4>MCQ Quiz Questions</h4>
                        @if($mcqQuestions->isEmpty())
                            <p>No MCQ questions available for this chapter.</p>
                        @else
                            <ul class="list-group">
                                @foreach($mcqQuestions as $question)
                                    <li class="list-group-item">
                                        <p><strong>{{ $question->question_text }}</strong></p>
                                        <p><strong>Score:</strong> {{ $question->score ?? 'N/A' }}</p>
                                        <p><strong>Attempts:</strong> {{ $question->attempts ?? 'N/A' }}</p>
                                        <p><strong>Total Points:</strong> {{ $question->total_points ?? 'N/A' }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>--}}

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .container {
        margin-top: 30px;
    }
    .card {
        border: none;
        border-radius: 10px;
    }
    .card-header {
        border-radius: 10px 10px 0 0;
    }
</style>
@endsection

@section('scripts')
<script>
    document.querySelector('.btn-success').addEventListener('click', function() {
        alert('Quiz submitted!');
    });

    // show/hidden reply button
    function toggleReplyForm(answerId) {
        const replyForm = document.getElementById(`replyForm_${answerId}`);
        if (replyForm.style.display === "none" || replyForm.style.display === "") {
            replyForm.style.display = "block"; // Show the form
        } else {
            replyForm.style.display = "none"; // Hide the form
        }
    }
</script>
@endsection
