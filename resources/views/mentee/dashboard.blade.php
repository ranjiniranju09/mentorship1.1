@extends('layouts.new_mentee')
@section('content')

<style>
    html, body {
        height: 100%;
        padding: 0;
        overflow-x: hidden;
    }

    .container {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap; /* Ensure items wrap to prevent overflow */
    }

    .row {
        width: 100%;
        margin: 0;
    }

    .col-md-8, .col-md-4 {
        padding: 15px;
        box-sizing: border-box;
    }

    .dashboard-header-wrapper {
        position: sticky;
        top: 0;
        z-index: 10;
        padding: 10px;
    }

    .custom-card {
        width: 100%;
        text-align: center;
        height: 30px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        transition: transform 0.3s;
    }

    .custom-card:hover {
        transform: scale(1.05);
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .mentor-details {
        width: 100%;
        padding: 15px;
        box-sizing: border-box;
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    table {
        table-layout: auto;
        width: 100%;
    }

    th, td {
        word-wrap: break-word;
        white-space: normal;
    }

    .academic-record, .assigned-tasks, .calendar, .mentor-details, .notifications, .recent-activities, .meetings {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        transition: transform 0.3s;
    }

    .progress {
        height: 20px;
        border-radius: 10px;
    }

    .module-name {
        font-size: 16px;
        color: #333;
        margin-bottom: 10px;
    }

    .calendar {
        max-width: 100%;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid #ddd;
    }

    .calendar-title {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        text-align: center;
    }

    .calendar-item {
        padding: 20px;
        border-bottom: 2px solid #eee;
        transition: all 0.3s ease-in-out;
        background-color: #fafafa;
        border-radius: 4px;
    }

    .calendar-item:last-child {
        border-bottom: none;
    }

    .calendar-item:hover {
        background-color: #f0f0f0;
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .calendar-time {
        font-size: 15px;
        color: #333;
        display: block;
        font-weight: bold;
    }

    .calendar-event {
        font-size: 16px;
        color: #666;
        display: block;
        margin-top: 5px;
    }

    .notification-item, .mentor-detailsitems, .activity-item {
        padding: 20px 0;
        border-bottom: 2px solid #eee;
        transition: all 0.3s ease-in-out;
    }

    .notification-item:last-child, .activity-item:last-child {
        border-bottom: none;
    }

    .notification-item:hover, .activity-item:hover {
        background-color: #f0f0f0;
        transform: scale(1.02);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .notification-time, .activity-time {
        font-size: 15px;
        color: #333;
        display: block;
        font-weight: bold;
    }

    .notification-event, .activity-event {
        font-size: 16px;
        color: #666;
        display: block;
    }

    .icon {
        font-size: 40px;
        margin-bottom: 20px;
        transition: transform 0.4s;
    }

    .custom-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: 100%;
        text-align: center;
        margin-bottom: 30px;
        transition: transform 0.3s;
    }

    .custom-card:hover {
        transform: translateY(-5px);
    }

    .custom-card.modules {
        border-left: 5px solid #007bff;
        height: 95%;
    }

    .custom-card.task {
        border-left: 5px solid #28a745;
        height: 95%;
    }

    .custom-card.resources {
        border-left: 5px solid #ffc107;
        height: 95%;
    }

    .custom-card.jobs {
        border-left: 5px solid #d24dff;
        height: 95%;
    }

    .custom-card.modules .card-icon {
        color: #007bff;
    }

    .custom-card.task .card-icon {
        color: #28a745;
    }

    .custom-card.resources .card-icon {
        color: #ffc107;
    }

    .custom-card.jobs .card-icon {
        color: #d24dff;
    }

    #calendar {
        max-width: 100%;
        margin: 0 auto;
    }

    .fc-daygrid-day-number {
        color: #333;
    }

    .fc-daygrid-day-top {
        background-color: #ececec;
        border-radius: 4px;
        padding: 5px;
    }

</style>

<div class="container">
    <div class="row">
        <!-- Right Column: Assigned Mentor Section (Displayed First on Mobile) -->
        <center><div class=" col-sm-10 order-md-1 order-0 mb-4">
            <div class="mentor-details p-3 align-content-center" style="background-color:#ffc107;">
                <center><h4>Assigned Mentor</h4>
                <p>Mentor name: {{$mentorName}}</p> </center>
                <p>Mentor Email: {{$mentorEmail}}</p>
               <p>Mobile No: {{$mentorMobile}}</p>
            </div>
        </div></center>

        <!-- Left Column: Content -->
        <div class="col-md-12 col-sm-12 order-md-2 order-1">
            <div class="topbar">
                {{--<div class="dashboard-header">
                    <i class="fa-solid fa-graduation-cap fa-beat fa-2xl"></i>
                    <span class="greeting">{{ Auth::user()->name }}</span>
                </div>--}}
            </div>

            <div class="row mt-4">
                <div class="col-6 col-md-3 mb-3">
                    <div class="custom-card modules text-center">
                        <a href="{{ route('menteesessions.index') }}"><i class="icon fa-solid fa-diagram-project fa-2xl"></i></a>
                        <h5>Modules</h5>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="custom-card task text-center">
                        <a href="{{ route('menteetasks.index') }}"><i class="icon fa-solid fa-list-check fa-2xl"></i></a>
                        <h5>Task</h5>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="custom-card resources text-center">
                        <a href="{{ route('knowledgebank.index') }}"><i class="icon fa-solid fa-link fa-2xl"></i></a>
                        <h5>Knowledge Bank</h5>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3">
                    <div class="custom-card jobs text-center">
                        <a href="{{ route('opportunities.index') }}"><i class="icon fa-solid fa-briefcase fa-2xl"></i></a>
                        <h5>Opportunities</h5>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h4>Sessions</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Module Name</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modules as $module)
                                    @if(is_null($module->deleted_at))
                                    <tr>
                                        <td>{{ $module->name }}</td>
                                        <td>
                                            @if(isset($sessions[$module->id]) && count($sessions[$module->id]) > 0)
                                                @foreach($sessions[$module->id] as $session)
                                                    @if(!empty($session->sessiondatetime))
                                                        <span class="badge bg-primary">Scheduled</span>
                                                    @else
                                                        <span class="badge bg-dark">Not Scheduled</span>
                                                    @endif
                                                @endforeach
                                            @else
                                                <span class="badge bg-dark">Not Scheduled</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($sessions[$module->id]) && count($sessions[$module->id]) > 0)
                                                @foreach($sessions[$module->id] as $session)
                                                    {{ $session->sessiondatetime }}<br>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($sessions[$module->id]) && count($sessions[$module->id]) > 0)
                                                @foreach($sessions[$module->id] as $session)
                                                    @if($session->done == 1)
                                                        <button class="btn btn-sm btn-secondary" disabled>Session Completed</button><br>
                                                    @else
                                                        <a href="{{ $session->sessionlink }}" target="_blank" class="btn btn-sm btn-primary">Join Session</a><br>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
