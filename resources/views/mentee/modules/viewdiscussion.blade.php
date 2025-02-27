@extends('layouts.new_mentee')

@section('content')
<div class="container mt-7">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2>{{ $chapter->title }} - Discussion Questions</h2>
                </div>
                <div class="card-body">
                    <p>Below are the discussion questions for this chapter:</p>

                    @if($discussionQuestions->isEmpty())
                        <p class="text-muted">No discussion questions available for this chapter.</p>
                    @else
                        <!-- Form for submitting answers -->
                        <form action="{{ route('discussionanswerstore', ['chapter_id' => $chapter->id]) }}" method="POST">
                            @csrf
                            <div id="question-wrapper">
                                @foreach($discussionQuestions as $index => $question)
                                    <div class="question-card" data-index="{{ $index }}" style="{{ $index === 0 ? '' : 'display: none;' }}">
                                        <strong>Question {{ $index + 1 }}:</strong>
                                        <p>{{ $question->question_text }}</p>

                                        <!-- Include hidden input for question_id -->
                                        <input type="hidden" name="answers[{{ $index }}][question_id]" value="{{ $question->question_id }}">

                                        <!-- Display mentee's previous answer if available -->
                                        <div class="form-group">
                                            <label for="discussionanswer_{{ $question->question_id }}">Your Answer</label>
                                            <textarea 
                                                class="form-control {{ $errors->has('answers.' . $index . '.discussion_answer') ? 'is-invalid' : '' }}" 
                                                name="answers[{{ $index }}][discussion_answer]" 
                                                id="discussionanswer_{{ $question->question_id }}" 
                                                rows="5">{{ $question->discussion_answer }}</textarea>

                                            @if($errors->has('answers.' . $index . '.discussion_answer'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('answers.' . $index . '.discussion_answer') }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Display mentor's reply if available -->
                                        <div class="form-group">
                                            <label for="mentorsreply_{{ $question->question_id }}">Mentor's Reply</label>
                                            <textarea 
                                                class="form-control" 
                                                id="mentorsreply_{{ $question->question_id }}" 
                                                rows="3" 
                                                readonly>{{ $question->mentorsreply ?? 'No reply from mentor yet.' }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Hidden field for module_id -->
                            <input type="hidden" name="module_id" value="{{ $module->id }}">
                            
                            <!-- Navigation Buttons -->
                            <div class="text-center mt-4">
                                <button id="prev-btn" class="btn btn-secondary" style="display: none;">Previous</button>
                                <button id="next-btn" class="btn btn-primary">Next</button>
                                <button id="submit-btn" class="btn btn-success" style="display: none;" type="submit">Submit Discussion</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .container {
        margin-top: 30px;
    }
    body {
        overflow-x: hidden; /* Ensure the entire page cannot scroll horizontally */
    }
    .card {
        border: none;
        border-radius: 10px;
    }
    .card-header {
        border-radius: 10px 10px 0 0;
    }
    .list-group-item {
        font-size: 1.1rem;
        color: #555;
    }
    .btn-secondary,
    .btn-primary,
    .btn-success {
        padding: 10px 20px;
        font-size: 1rem;
    }
    textarea {
        visibility: visible !important;
        opacity: 1 !important;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const questions = document.querySelectorAll('.question-card');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const submitBtn = document.getElementById('submit-btn');
        let currentIndex = 0;

        function updateButtons() {
            prevBtn.style.display = currentIndex === 0 ? 'none' : 'inline-block';
            nextBtn.style.display = currentIndex === questions.length - 1 ? 'none' : 'inline-block';
            submitBtn.style.display = currentIndex === questions.length - 1 ? 'inline-block' : 'none';
        }

        function showQuestion(index) {
            questions.forEach((question, i) => {
                question.style.display = i === index ? '' : 'none';
            });
            updateButtons();
        }

        prevBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (currentIndex > 0) {
                currentIndex--;
                showQuestion(currentIndex);
            }
        });

        nextBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (currentIndex < questions.length - 1) {
                currentIndex++;
                showQuestion(currentIndex);
            }
        });

        showQuestion(currentIndex);
    });
</script>
@endsection
