@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.guestLecture.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.guest-lectures.index') }}">
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
                            {{ $guestLecture->speaker->speakername ?? '' }} <!-- Corrected 'speaker' relationship -->
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.guestLecture.fields.invitedmentees') }}
                        </th>
                        <td>
                            @foreach($guestLecture->invitedMentees as $invited_mentee) <!-- Corrected 'invitedMentees' relationship -->
                                <span class="label label-info">{{ $invited_mentee->name }}</span>
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
                <a class="btn btn-default" href="{{ route('admin.guest-lectures.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
