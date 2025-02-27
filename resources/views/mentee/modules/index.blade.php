@extends('layouts.new_mentee')
@section('content')


<style type="text/css">
    .content {
        padding: 10px;
        float: right;
        width: 95%;
    }
    .module-card {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
        height: 350px; /* Set a fixed height to ensure uniform size */
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* Keeps "Explore" button at the bottom */
    }
    .module-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }
    .module-title {
        color: #007bff;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .module-name {
        color: #555;
        font-size: 18px;
        margin-bottom: 10px;
        font-weight: bold;
    }
    .module-description {
        color: #555;
        font-size: 16px;
        flex-grow: 1; /* Allows the description area to grow within the card */
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 20px;
    }
    .module-action {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .action-btn {
        background-color: #007bff;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 16px;
        font-weight: bold;
    }
    .action-btn:hover {
        background-color: #0056b3;
    }
    /* Status icons */
    .status-icons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .status {
        display: inline-flex;
        align-items: center;
        font-size: 0.9rem;
        padding: 4px 8px;
        border-radius: 4px;
        background-color: #f1f1f1;
        color: #555;
        transition: background-color 0.3s;
    }

    .status i {
        margin-right: 4px;
    }

    .status.mastered { background-color: #5a4b81; color: white; }
    .status.proficient { background-color:rgb(64, 186, 98); color: white; }
    .status.familiar { background-color: #ffbf69; color: white; }
    .status.attempted { background-color: #ff7f50; color: white; }
    .status.not-started, .status.quiz { background-color: #d3d3d3; color: black; }
    .status.unit-test { background-color: #333333; color: white; }


</style>
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
<center><h1>Modules</h1></center>
<hr>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="content">
                <!-- Modules -->
                <div class="container mt-3">
                    <h1 class="page-title">Explore Modules</h1>
                    <br>
                    <div class="row">
                        @foreach($modules as $module)
                        <div class="col-md-4">
                            <div class="module-card">
                                @php
                                    // Retrieve maxResult for the current module
                                    $maxResult = $maxResults->get($module->id) ?? (object) ['max_score' => null];
                                @endphp

                                @if($maxResult && $maxResult->max_score === 8)
                                    <span class="status mastered" title="Mastered"><i class="fa-solid fa-crown"></i> Mastered</span>
                                @elseif($maxResult && $maxResult->max_score === 5)
                                    <span class="status proficient" title="Proficient"><i class="fa-solid fa-user-graduate"></i> Proficient</span>
                                @elseif($maxResult && $maxResult->max_score === 0)
                                    <span class="status attempted" title="Attempted"><i class="fa-solid fa-xmark"></i> Poor</span>
                                @else
                                    <span class="status not-started" title="Not Started"><i class="fa-solid fa-circle-xmark"></i> Quiz Not Started</span>
                                @endif

                                <h2 class="module-name">{{ $module->name ?? '' }}</h2>
                                <p class="module-objective">
                                    <strong>Objective: </strong>{{ Str::limit($module->objective, 150) ?? '' }}
                                </p>
                                <div class="module-action">
                                    <a href="{{ route('chapterscontent', ['module_id' => $module->id]) }}" class="action-btn" target="_blank">Explore</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
@endsection
