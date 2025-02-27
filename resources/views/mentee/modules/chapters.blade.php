@extends('layouts.new_mentee')
@section('content')

<style>
    /* Existing and new styles */

    /* Adjusted Styles */

/* Container adjustments */
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


</style>

@if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

<div class="container ">
    <h2 class="text-center mb-3">{{ $module->name }}</h2>
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
            <div id="chapter-details">
                @foreach($chapters as $chapter)
                    <div id="chapter-content-{{ $chapter->id }}" class="chapter-content" style="display: none;">
                        <h4>{{ $chapter->chaptername }}</h4>
                        <hr>

                        <p>{{ $chapter->description }}</p>
                        <p><small class="text-body-secondary">Last updated 3 mins ago</small></p>

                        <div class="nav-buttons">
                            @if($chapter->subchapters && $chapter->subchapters->count() > 0)
                                <a href="{{ route('subchaptercontent', ['chapter_id' => $chapter->id]) }}" class="btn btn-primary">Get Started</a>
                            @else
                                <!-- The button will not be displayed if no subchapters exist -->
                            @endif

                            @if($chapter->has_mcq)
                                <a href="{{ route('viewquiz', ['chapter_id' => $chapter->id]) }}" class="btn">Start Quiz</a>
                            @else
                                <a href="{{ route('getDiscussionQuestions', ['chapter_id' => $chapter->id]) }}" class="btn">
                                    Discussion Points
                                </a>
                            @endif
                        </div>

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
    console.log("Showing chapter ID:", chapterId); // Debugging line
    document.querySelectorAll('.chapter-content').forEach(content => content.style.display = 'none');
    document.getElementById('chapter-content-' + chapterId).style.display = 'block';
}

    document.addEventListener('DOMContentLoaded', () => {
        const firstChapter = document.querySelector('.chapter-content');
        if (firstChapter) {
            firstChapter.style.display = 'block';
        }
    });
    

</script>
@endsection
