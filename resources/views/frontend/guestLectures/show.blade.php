@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.guestLecture.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.guest-lectures.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.guestLecture.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $guestLecture->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.guestLecture.fields.guessionsession_title') }}
                                    </th>
                                    <td>
                                        {{ $guestLecture->guessionsession_title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.guestLecture.fields.speaker_name') }}
                                    </th>
                                    <td>
                                        {{ $guestLecture->speaker_name->speakername ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.guestLecture.fields.invited_mentees') }}
                                    </th>
                                    <td>
                                        @foreach($guestLecture->invited_mentees as $key => $invited_mentees)
                                            <span class="label label-info">{{ $invited_mentees->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.guestLecture.fields.guestsession_date_time') }}
                                    </th>
                                    <td>
                                        {{ $guestLecture->guestsession_date_time }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.guestLecture.fields.guest_session_duration') }}
                                    </th>
                                    <td>
                                        {{ App\GuestLecture::GUEST_SESSION_DURATION_RADIO[$guestLecture->guest_session_duration] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.guestLecture.fields.platform') }}
                                    </th>
                                    <td>
                                        {{ App\GuestLecture::PLATFORM_SELECT[$guestLecture->platform] ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.guest-lectures.index') }}">
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