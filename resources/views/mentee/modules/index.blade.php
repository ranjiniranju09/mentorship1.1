@extends('layouts.new_mentee')
@section('content')


<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f8f9fa;
    }

    .content {
        padding: 10px;
        float: right;
        width: 100%;
    }

    .page-title {
        font-size: 2rem;
        font-weight: bold;
        text-align: center;
        margin-bottom: 30px;
        color: #333;
    }

    .module-card {
        background: linear-gradient(to bottom right, #ffffff, #f1f1f1);
        padding: 25px;
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        height: 370px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s ease-in-out;
        border: 1px solid #e0e0e0;
    }

    .module-card:hover {
        transform: scale(1.02);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
    }

    .module-name {
        font-size: 20px;
        color: #333;
        font-weight: 600;
        margin-top: 10px;
    }

    .module-objective {
        font-size: 15px;
        color: #555;
        margin-top: 10px;
        flex-grow: 1;
    }

    .module-action {
        display: flex;
        justify-content: flex-end;
        margin-top: 15px;
    }

    .action-btn {
        background: linear-gradient(to right, #007bff, #0056b3);
        color: #fff;
        padding: 10px 24px;
        border: none;
        border-radius: 30px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease-in-out;
        position: relative;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
        text-decoration: none !important;
    }

    .action-btn:hover {
        background: linear-gradient(to right, #0056b3, #003e80);
        color: #fff ; 
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0, 86, 179, 0.4);
        text-decoration: none !important;
    }

    .action-btn i {
        font-size: 16px;
    }


    .status {
        display: inline-flex;
        align-items: center;
        font-size: 0.85rem;
        padding: 6px 12px;
        border-radius: 30px;
        font-weight: 500;
        margin-bottom: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    .status i {
        margin-right: 6px;
    }

    .status.mastered    { background-color: #5a4b81; color: #fff; }
    .status.proficient  { background-color: #28a745; color: #fff; }
    .status.familiar    { background-color: #ffc107; color: #000; }
    .status.attempted   { background-color: #fd7e14; color: #fff; }
    .status.not-started { background-color: #d6d8db; color: #000; }

    @media (max-width: 768px) {
        .module-card {
            height: auto;
        }
        .action-btn {
            width: 100%;
            text-align: center;
        }
    }
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
<!-- <center><h1>Modules</h1></center>
<hr> -->
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
                                    $maxResult = $maxResults->get($module->id) ?? (object) ['max_score' => null];
                                    $score = $maxResult->max_score;

                                    // Badge logic
                                    $badge = [
                                        'class' => 'not-started',
                                        'icon' => 'fa-solid fa-circle-xmark',
                                        'text' => 'Not Started'
                                    ];

                                    if ($score === 8) {
                                        $badge = ['class' => 'mastered', 'icon' => 'fa-solid fa-crown', 'text' => 'Mastered'];
                                    } elseif ($score >= 6) {
                                        $badge = ['class' => 'proficient', 'icon' => 'fa-solid fa-user-graduate', 'text' => 'Proficient'];
                                    } elseif ($score >= 3) {
                                        $badge = ['class' => 'familiar', 'icon' => 'fa-solid fa-user', 'text' => 'Familiar'];
                                    } elseif ($score > 0) {
                                        $badge = ['class' => 'attempted', 'icon' => 'fa-solid fa-xmark', 'text' => 'Attempted'];
                                    }
                                @endphp

                                <span class="status {{ $badge['class'] }}" title="{{ $badge['text'] }}">
                                    <i class="{{ $badge['icon'] }}"></i> {{ $badge['text'] }}
                                </span>

                                @if ($score !== null)
                                    <div class="progress" style="height: 8px; margin-top: 10px; border-radius: 4px;">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: {{ ($score / 8) * 100 }}%;"
                                            aria-valuenow="{{ $score }}"
                                            aria-valuemin="0"
                                            aria-valuemax="8">
                                        </div>
                                    </div>
                                @endif

                                <h2 class="module-name">{{ $module->name ?? '' }}</h2>
                                <p class="module-objective">
                                    <strong>Objective: </strong>{{ Str::limit($module->objective, 150) ?? '' }}
                                </p>
                                <div class="module-action">
                                    <a href="{{ route('chapterscontent', ['module_id' => $module->id]) }}" class="action-btn" target="_blank">
                                        <i class="fa-solid fa-arrow-right"></i> Explore
                                    </a>
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
