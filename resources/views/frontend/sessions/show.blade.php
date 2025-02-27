@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.session.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.sessions.index') }}">
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
                            <a class="btn btn-default" href="{{ route('frontend.sessions.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection