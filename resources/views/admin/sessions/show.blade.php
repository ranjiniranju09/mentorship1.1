@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.session.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sessions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.session.fields.id') }}
                        </th>
                        <td>
                            {{ $session->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.session.fields.modulename') }}
                        </th>
                        <td>
                            {{ $session->modulename->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.session.fields.mentorname') }}
                        </th>
                        <td>
                            {{ $session->mentorname->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.session.fields.menteename') }}
                        </th>
                        <td>
                            {{ $session->menteename->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.session.fields.sessiondatetime') }}
                        </th>
                        <td>
                            {{ $session->sessiondatetime }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.session.fields.sessionlink') }}
                        </th>
                        <td>
                            {{ $session->sessionlink }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.session.fields.session_title') }}
                        </th>
                        <td>
                            {{ $session->session_title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.session.fields.session_duration_minutes') }}
                        </th>
                        <td>
                            {{ App\Session::SESSION_DURATION_MINUTES_RADIO[$session->session_duration_minutes] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sessions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#session_title_session_recordings" role="tab" data-toggle="tab">
                {{ trans('cruds.sessionRecording.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#session_attendance_one_one_attendances" role="tab" data-toggle="tab">
                {{ trans('cruds.oneOneAttendance.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#session_title_guest_session_attendances" role="tab" data-toggle="tab">
                {{ trans('cruds.guestSessionAttendance.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="session_title_session_recordings">
            @includeIf('admin.sessions.relationships.sessionTitleSessionRecordings', ['sessionRecordings' => $session->sessionTitleSessionRecordings])
        </div>
        <div class="tab-pane" role="tabpanel" id="session_attendance_one_one_attendances">
            @includeIf('admin.sessions.relationships.sessionAttendanceOneOneAttendances', ['oneOneAttendances' => $session->sessionAttendanceOneOneAttendances])
        </div>
        <div class="tab-pane" role="tabpanel" id="session_title_guest_session_attendances">
            @includeIf('admin.sessions.relationships.sessionTitleGuestSessionAttendances', ['guestSessionAttendances' => $session->sessionTitleGuestSessionAttendances])
        </div>
    </div>
</div>

@endsection