@extends('layouts.mentor')
@section('content')

<style>
    .container {
    margin: 10px auto;
    /* max-width:; Ensure it doesn't overflow the viewport width */
    padding: 0 15px; /* Add padding to prevent content from touching edges */
}
body {
    overflow-x: hidden; /* Ensure the entire page cannot scroll horizontally */
}

/* List group items */
.list-group-item {
    background-color: #f8f9fa !important;
    border: none;
    border-left: 4px solid transparent;
    color: #007bff;
    font-weight: bold;
    transition: all 0.3s ease;
    position: relative;
}

/* Separator line between items */
.list-group-item:not(:last-child)::after {
    content: "";
    display: block;
    width: 100%;
    height: 1px;
    background-color: #dcdcdc;
    position: absolute;
    bottom: 0;
    left: 0;
}

/* Navigation buttons */
.nav-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.nav-buttons .btn {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
    font-size: 16px;
    font-weight: bold;
}

.nav-buttons .btn:hover {
    background-color: #0056b3;
}

/* Chapter content section */
#chapter-details {
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    height: 100%; /* Ensure the content adjusts to screen height */
}

.chapter-content h4 {
    margin-top: 20px;
    font-size: 1.5rem;
    color: #343a40;
}

.chapter-content p {
    font-size: 1rem;
    color: #555;
    line-height: 1.6;
}

    .scrollspy-example {
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-height: 80vh;
        overflow-y: auto;
    }

    .scrollspy-example h4 {
        margin-top: 30px;
        font-size: 1.5rem;
        color: #343a40;
    }

    .scrollspy-example p {
        font-size: 1rem;
        color: #555;
        line-height: 1.6;
    }

    .collapse {
        background-color: #f9f9f9;
        padding: 10px;
        border-radius: 5px;
    }
</style>

@if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

<div class="container">
    <h2 class="text-center mb-3">{{ $module->name }}</h2>

    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <!-- Home -->
            <li class="breadcrumb-item"><a href="{{ route('mentor.dashboard') }}">Home</a></li>

            <!-- Modules -->
            <li class="breadcrumb-item"><a href="{{ route('mentor.modules') }}">Modules</a></li>

            <!-- Current Module Name -->
            @if(isset($module))
                <li class="breadcrumb-item">
                    <a href="{{ route('showmentorchapters', ['module_id' => $module->id]) }}">
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


    <!-- <hr> -->
    <div class="row">
        <div class="col-md-4">
            <div id="list-example" class="list-group">
                @foreach($chapters as $chapter)
                    <a class="list-group-item list-group-item-action" href="javascript:void(0);" onclick="showChapter({{ $chapter->id }})">
                        {{ $loop->iteration }}. {{ $chapter->chaptername }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="col-md-8">
            <div class="scrollspy-example" id="chapter-details">
            @foreach($chapters as $chapter)
                <div id="chapter-content-{{ $chapter->id }}" class="chapter-content" style="display: none;">
                    <h4>{{ $chapter->chaptername }}</h4>
                    <hr>

                    <p>{{ $chapter->description }}</p>
                    <p><small class="text-body-secondary">Last updated {{ $chapter->updated_at->diffForHumans() }}</small></p>

                    <div class="nav-buttons">
                        <a href="{{ route('mentor.mentorsubchapter', ['chapter_id' => $chapter->id]) }}" class="btn">Get Started</a>


                        <!-- <a href="{{ route('mentor.quiz', ['chapter_id' => $chapter->id]) }}" class="btn">Quiz Response</a> -->
                        <a href="{{ route('mentor.quiz', ['chapter_id' => $chapter->id]) }}" class="btn">Quiz Response</a>


                        <!-- Collapsible button for Discussion Points -->
                        @if($chapter->mentorsnote)
                            <button class="btn" data-bs-toggle="collapse" data-bs-target="#discussion-{{ $chapter->id }}" aria-expanded="false" aria-controls="discussion-{{ $chapter->id }}">
                                Discussion KeyPoints
                            </button>
                        @endif
                    </div>
                        <!-- Collapsible content for Discussion Points -->
                        @if($chapter->mentorsnote)
                            <div id="discussion-{{ $chapter->id }}" class="collapse mt-3">
                                <p>
                                    {{ $chapter->mentorsnote }}
                                </p>
                            </div>
                        @endif


                </div>
            @endforeach

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function showChapter(chapterId) {
        // Hide all chapter content
        document.querySelectorAll('.chapter-content').forEach(content => content.style.display = 'none');

        // Show the selected chapter's content
        document.getElementById('chapter-content-' + chapterId).style.display = 'block';
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Display the first chapter's content by default
        const firstChapter = document.querySelector('.chapter-content');
        if (firstChapter) {
            firstChapter.style.display = 'block';
        }
    });
</script>
@endsection
