@extends('layouts.mentor')
@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<style>
    @import url("https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap");

    * {
        font-family: "Ubuntu", sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --blue: #2a2185;
        --white: #fff;
        --gray: #f5f5f5;
        --black1: #222;
        --black2: #999;
    }

    body {
        min-height: 100vh;
        overflow-x: hidden;
        background: var(--white);
    }

    .container {
        position: relative;
        width: calc(100% - 420px); /* Adjust width to accommodate fixed card */
        margin-left: 0;
        /* padding: 20px; */
    }

    .topbar, .cardBox {
        margin-bottom: 20px;
    }

    .topbar {
        width: 100%;
        height: 60px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
        background: var(--white);
        box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
    }

    .mentee-card {
        position: fixed;
        top: 80px;
        right: 20px;
        width: 380px;
        padding: 15px;
        background-color: var(--white);
        border: 1px solid #ddd;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        text-align: center;
        z-index: 1000;
    }

    .mentee-card h5 {
        font-size: 1.2rem;
        margin-bottom: 8px;
    }

    .mentee-card p {
        font-size: 0.9rem;
        color: #666;
    }

    .cardBox a {
    text-decoration: none !important;
    color: inherit; /* Optional: Ensures the color doesn't change */

    }

    .cardBox {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 20px;
    }

    .card {
        background: var(--white);
        height: auto;
        min-height: 150px;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        cursor: pointer;
        transition: background 0.3s;
        color: var(--black1);
    }
    .cardBox a:hover {
        text-decoration: none; /* Optional: Add underline on hover if needed */
    }
    .card:hover {
        background: var(--black1);
        color: var(--white);
    }

    .card .numbers {
        font-size: 1.5rem;
        font-weight: 500;
    }

    .card .cardName {
        font-size: 1rem;
    }

    .iconBx {
        font-size: 1.5rem;
    }

    @media (max-width: 768px) {
        .mentee-card {
            position: static;
            margin-bottom: 20px;
            width: 100%;
        }

        .container {
            width: 100%;
            margin-left: 0;
        }

        .card .numbers {
            font-size: 1.2rem;
        }

        .card .cardName {
            font-size: 0.9rem;
        }

        .iconBx {
            font-size: 1.2rem;
        }
    }
</style>

<div class="mentee-card mt-5">
    @if($menteeDetails)
        <div>
            <h5>Mapped Mentee</h5>
            <p><strong>Name:</strong> {{ $menteeDetails->name }}</p>
            <p><strong>Email:</strong> {{ $menteeDetails->email }}</p>
            <p><strong>Mobile:</strong> {{ $menteeDetails->mobile }}</p>
        </div>
    @else
        <div>
            <h5>Mapped Mentee</h5>
            <p>No mentee mapped.</p>
        </div>
    @endif
</div>
<div class="container">
    <h3 class="mb-2">Welcome, Mentor</h3>
    <br>
    <br>
    <div class="col-md-10">
        <div class="cardBox">
            <a href="{{route('mentor.modules')}}" target="_blank">
                <div class="card">
                    <div class="cardName">Modules</div>
                    <div class="iconBx"><i class="fa-solid fa fa-sitemap"></i></div>
                </div>
            </a>
            <a href="{{route('quizdetails')}}" target="_blank">
                <div class="card">
                    <div class="cardName">Total Quizzes</div>
                    <div class="iconBx"><i class="fa-regular fa-circle-question"></i></div>
                </div>
            </a>
            <a href="{{route('tasks.index')}}" target="_blank">
                <div class="card">
                    <div class="cardName">Tasks</div>
                    <div class="iconBx"><i class="fa fa-tasks"></i></div>
                </div>
            </a>
            <a href="{{route('sessions.index')}}" target="_blank">
                <div class="card">
                    <div class="cardName">Total One - One Sessions</div>
                    <div class="iconBx"><i class="fa-solid fa-users"></i></div>
                </div>
            </a>
            <div class="card">
                <div class="cardName">Total Minutes Mentored</div>
                <div class="iconBx">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div class="totalMinutes">
                    {{ $totalMinutes }} minutes
                </div>
            </div>

            <a href="{{route('resources.index')}}" target="_blank">
                <div class="card">
                    <div class="cardName">Total Resources</div>
                    <div class="iconBx"><i class="fa-solid fa-link"></i></div>
                </div>
            </a>
            <a href="{{route('opportunity.index')}}" target="_blank">
                <div class="card">
                    <div class="cardName">Opportunities</div>
                    <div class="iconBx"><i class="fa-solid fa-briefcase"></i></div>
                </div>
            </a>
        </div>
    </div>
</div>

@endsection
