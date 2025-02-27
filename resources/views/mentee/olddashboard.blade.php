
@extends('layouts.mentee')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    <h2>Welcome Mentee</h2>
                </div>
            </div>
            
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Assigned Mentor</div>
                <div class="card-body">
                    <strong>Mentor Name:</strong>
                    {{$mentorName}}
                    <hr>
                    <a href="" class="btn btn-xs btn-primary">View Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row justify-content-center">
        
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Mentorship Meetings</div>
                <div class="card-body">
                   <table class="table table-striped ">
                       <thead>
                           <th scope="col">Module Name</th>
                           <th scope="col">Status</th>
                           <th scope="col">Date</th>
                           <th scope="col">Action</th>
                       </thead>
                       <tbody>
                        @foreach($modules as $module)
                        <tr>
                            <td>{{ $module->name }}</td>
                            <td>
                                {{--
                                @if($module->status == 'Done')
                                    <span class="badge badge-success">Done</span>
                                @elseif($module->status == 'Scheduled')
                                    <span class="badge badge-primary">Scheduled</span>
                                @else
                                    <span class="badge badge-dark">Not Scheduled</span>
                                @endif
                                
                                    <span class="badge badge-success">Done</span>
                                    <span class="badge badge-primary">Scheduled</span>
                                    <span class="badge badge-dark">Not Scheduled</span>--}}
                                    @if(isset($sessions[$module->id]) && count($sessions[$module->id]) > 0)
                                    @foreach($sessions[$module->id] as $session)
                                        @if(!empty($session->sessiondatetime))
                                            <span class="badge badge-primary">Scheduled</span>
                                        @else
                                            <span class="badge badge-dark">Not Scheduled</span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="badge badge-dark">Not Scheduled</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($sessions[$module->id]) && count($sessions[$module->id]) > 0)
                                    @foreach($sessions[$module->id] as $session)
                                        {{ $session->sessiondatetime }}<br>
                                    @endforeach
                                @else
                                    
                                @endif
                            </td>
                            <td>
                                @if(isset($sessions[$module->id]) && count($sessions[$module->id]) > 0)
                                    @foreach($sessions[$module->id] as $session)
                                        <a href="{{ $session->sessionlink }}" target="_blank" class="btn btn-xs btn-primary">Join Session</a><br>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        @endforeach
                       
                       </tbody>
                   </table>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Assigned Tasks</div>
                <div class="card-body">
                    <table class="table table-striped ">
                       <thead>
                            <th scope="col" class="col-lg-9">Assigned Task</th>
                            <th scope="col" class="col-lg-3">Submission Done</th>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                            <tr>
                                <td>
                                    {{$task->title}}
                                </td>
                                <td>
                                    @if(empty($task->task_response))
                                        <span class="badge badge-danger">No</span>
                                    @else
                                        <span class="badge badge-success">Yes</span>
                                    @endif

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>
<!-- Existing dashboard content -->

<!-- Guest Lecture Section -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Guest Lectures</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <th scope="col">Title</th>
                            <th scope="col">Date and Time</th>
                            <th scope="col">Duration</th>
                            <th scope="col">Platform</th>
                        </thead>
                        <tbody>
                            @foreach($guestLectures as $lecture)
                            <tr>
                                <td>{{ $lecture->guessionsession_title }}</td>
                                <td>{{ $lecture->guestsession_date_time }}</td>
                                <td>{{ $lecture->guest_session_duration }} mins</td>
                                <td>{{ $lecture->platform }}</td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
