@extends('layouts.new_mentee')

@section('content')
<div class="container mt-5">
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<!-- Breadcrumb code inserted here -->
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <!-- Home -->
            <li class="breadcrumb-item"><a href="{{ route('mentee.dashboard') }}">Home</a></li>

            <!-- Modules -->
            <li class="breadcrumb-item"><a href="{{ route('menteesessions.index') }}">Modules</a></li>

            <!-- Current Module Name -->
            @if(isset($module))
                <li class="breadcrumb-item">
                    <a href="{{ route('chapterscontent', ['module_id' => $module->id]) }}">
                        {{ $module->name }}
                    </a>
                </li>
            @endif

            <!-- Current Chapter Name -->
            @if(isset($currentChapter))
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $currentChapter->chaptername }}
                </li>
            @endif
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2>{{ $chapter->title }} - Quiz</h2>
                </div>
                <div class="card-body">
                    <p>Answer the following questions based on the chapter content.</p>
                    <form action="{{ route('quiz.submit') }}" method="POST">
                        @csrf

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
                        <input type="hidden" name="module_id" value="{{ $chapter->module_id }}">

                        @foreach($tests as $test)
                            <input type="hidden" name="test_id[]" value="{{ $test->id }}">
                        @endforeach

                      

                        <div id="question-wrapper">
                            @foreach($tests as $test)
                                <div class="quiz-section mb-4">
                                    <h3>{{ $test->title }}</h3>
                                    <p>{{ $test->description }}</p>


                                    @foreach($test->questions as $index => $question)
                                        <div class="question mb-3" data-index="{{ $index }}" style="{{ $index === 0 ? '' : 'display: none;' }}">
                                            <p><strong>{{ $loop->iteration }}. {{ $question->question_text }}</strong></p>
                                            <div class="options">
                                                @foreach($question->options as $option)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="question_{{ $question->id }}" id="option_{{ $option->id }}" value="{{ $option->id }}">
                                                        <label class="form-check-label" for="option_{{ $option->id }}">
                                                            {{ $option->option_text }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="text-center mt-4">
                            <button id="prev-btn" class="btn btn-secondary" style="display: none;">Previous</button>
                            <button id="next-btn" class="btn btn-primary">Next</button>
                            <button id="submit-btn" class="btn btn-success" style="display: none;" type="submit">Submit Quiz</button>
                        </div>
                    </form>
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
    .form-check-label {
        font-size: 1rem;
        color: #555;
    }
    .form-check-input:checked + .form-check-label {
        color: #007bff;
    }
    .btn-success {
        padding: 10px 20px;
        font-size: 1.2rem;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const questions = document.querySelectorAll('.question');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const submitBtn = document.getElementById('submit-btn');
        
        let currentIndex = 0;

        // Update navigation button visibility
        function updateButtons() {
            prevBtn.style.display = currentIndex === 0 ? 'none' : 'inline-block';
            nextBtn.style.display = currentIndex === questions.length - 1 ? 'none' : 'inline-block';
            submitBtn.style.display = currentIndex === questions.length - 1 ? 'inline-block' : 'none';
        }

        // Show the question at the specified index
        function showQuestion(index) {
            questions.forEach((question, i) => {
                question.style.display = i === index ? '' : 'none'; // Show only the current question
            });

            updateButtons();
        }

        // Previous question event
        prevBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (currentIndex > 0) {
                currentIndex--;
                showQuestion(currentIndex);
            }
        });

        // Next question event
        nextBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (currentIndex < questions.length - 1) {
                currentIndex++;
                showQuestion(currentIndex);
            }
        });

        // Show the first question initially
        showQuestion(currentIndex);
    });
</script>

<script>
    function showSubchapter(id) {
        // Hide all subchapters
        document.querySelectorAll('.subchapter-content').forEach(function(content) {
            content.style.display = 'none';
        });
        
        // Show the selected subchapter
        document.getElementById('subchapter-' + id).style.display = 'block';
    }

    // Show the first subchapter by default
    document.addEventListener('DOMContentLoaded', function() {
        const firstSubchapter = document.querySelector('.subchapter-content');
        if (firstSubchapter) {
            firstSubchapter.style.display = 'block';
        }
    });
</script>
@endsection
