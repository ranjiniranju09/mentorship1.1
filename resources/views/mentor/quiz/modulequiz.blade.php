@extends('layouts.mentor')

@section('content')
<div class="container mt-5">
    <h3 class="text-center mb-4 text-primary fw-bold">Module-wise MCQ Quiz Questions & Answers</h3>

    <div class="accordion" id="moduleAccordion">
        @forelse ($moduleData as $index => $entry)
            <div class="accordion-item mb-3 shadow-sm">
                <h2 class="accordion-header" id="heading{{ $index }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                        <strong>{{ $index + 1 }}. {{ $entry['module']->name }}</strong>
                    </button>
                </h2>
                <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#moduleAccordion">
                    <div class="accordion-body bg-light">
                        @forelse ($entry['questions'] as $qIndex => $question)
                            <div class="mb-4 p-3 bg-white rounded shadow-sm">
                                <p class="mb-2 fw-semibold">
                                    <span class="badge bg-secondary me-2">Q{{ $qIndex + 1 }}</span> {{ $question->question_text }}
                                </p>
                                <p class="mb-1">
                                    <strong>Correct Answer:</strong><br>
                                    @foreach ($question->options as $option)
                                        @if ($option->is_correct)
                                            <span class="badge bg-success fs-6 mt-1">{{ $option->option_text }}</span>
                                        @endif
                                    @endforeach
                                </p>
                            </div>
                        @empty
                            <p class="text-muted">No MCQ questions found for this module.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center">No modules with quizzes found.</div>
        @endforelse
    </div>
</div>
@endsection

@section('styles')
<style>
    .accordion-button {
        background: linear-gradient(to right, #007bff, #0056b3);
        color: white;
        font-weight: bold;
        padding: 1rem;
    }

    .accordion-button:not(.collapsed) {
        box-shadow: inset 0 -1px 0 rgba(6, 6, 6, 0.13);
        color: white;
    }

    /* Make the accordion arrow white and bold */
    .accordion-button::after {
        filter: invert(100%);
        font-weight: bold;
        transform: scale(1.3); /* make it larger */
    }

    .badge.bg-success {
        font-size: 1rem;
        padding: 0.5em 0.75em;
    }

    .accordion-body {
        font-size: 1rem;
        line-height: 1.5;
    }

    @media (max-width: 576px) {
        .accordion-button {
            padding: 0.8rem;
        }

        .badge.bg-success {
            font-size: 0.9rem;
            padding: 0.3em 0.5em;
        }

        .accordion-body {
            font-size: 0.9rem;
        }
    }
</style>
@endsection
