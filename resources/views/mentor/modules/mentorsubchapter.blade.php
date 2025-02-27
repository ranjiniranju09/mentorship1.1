@extends('layouts.mentor')

@section('content')

<style>
    /* Custom CSS styles for a professional look */
    .container {
        margin-top: 30px;
    }
    .list-group-item {
        background-color: #f8f9fa;
        border: none;
        border-left: 4px solid transparent;
        color: #007bff;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    .list-group-item:hover, .list-group-item.active {
        background-color: #007bff;
        color: #fff;
        border-left: 4px solid #0056b3;
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
</style>

@if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

<div class="container">
    <div class="row">
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
        <div class="col-md-4">
            <div id="list-example" class="list-group">
                
                @foreach($subchapters as $index => $subchapter)
                    <a class="list-group-item list-group-item-action" href="javascript:void(0);" onclick="showSubchapter({{ $subchapter->id }})">
                        {{ $index + 1 }}. {{ $subchapter->title }}
                    </a>
                @endforeach
                <a class="list-group-item list-group-item-action" href="javascript:void(0);" onclick="showSubchapter('resources')">Resource List</a>
            </div>
        </div>
        <div class="col-md-8">
            <div class="scrollspy-example" id="subchapter-content">
                @foreach($subchapters as $subchapter)
                    <div id="subchapter-{{ $subchapter->id }}" class="subchapter-content" style="display: none;">
                        <h4>{{ $subchapter->title }}</h4>
                        <p>{!! $subchapter->content !!}</p>
                    </div>
                @endforeach
                
                {{--<!-- <div id="subchapter-resources" class="subchapter-content" style="display: none;">
                    <h4>Resource List</h4>
                    <ul>
                        @foreach($moduleresources as $resource)
                            <li>
                                <strong>Title:</strong> {{ $resource->title }}<br>
                                <strong>Link:</strong> <a href="{{ $resource->link }}">{{ $resource->link }}</a><br>
                                <strong>Descriptive Material:</strong> {{ $resource->descriptivematerial }}
                            </li>
                        @endforeach
                    </ul>
                </div> -->--}}
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
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
