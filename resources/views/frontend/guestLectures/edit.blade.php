@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.guestLecture.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.guest-lectures.update", [$guestLecture->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="guessionsession_title">{{ trans('cruds.guestLecture.fields.guessionsession_title') }}</label>
                            <input class="form-control" type="text" name="guessionsession_title" id="guessionsession_title" value="{{ old('guessionsession_title', $guestLecture->guessionsession_title) }}" required>
                            @if($errors->has('guessionsession_title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('guessionsession_title') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.guestLecture.fields.guessionsession_title_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="speaker_name_id">{{ trans('cruds.guestLecture.fields.speaker_name') }}</label>
                            <select class="form-control select2" name="speaker_name_id" id="speaker_name_id">
                                @foreach($speaker_names as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('speaker_name_id') ? old('speaker_name_id') : $guestLecture->speaker_name->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('speaker_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('speaker_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.guestLecture.fields.speaker_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="invited_mentees">{{ trans('cruds.guestLecture.fields.invited_mentees') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="invited_mentees[]" id="invited_mentees" multiple required>
                                @foreach($invited_mentees as $id => $invited_mentee)
                                    <option value="{{ $id }}" {{ (in_array($id, old('invited_mentees', [])) || $guestLecture->invited_mentees->contains($id)) ? 'selected' : '' }}>{{ $invited_mentee }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('invited_mentees'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('invited_mentees') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.guestLecture.fields.invited_mentees_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="guestsession_date_time">{{ trans('cruds.guestLecture.fields.guestsession_date_time') }}</label>
                            <input class="form-control datetime" type="text" name="guestsession_date_time" id="guestsession_date_time" value="{{ old('guestsession_date_time', $guestLecture->guestsession_date_time) }}" required>
                            @if($errors->has('guestsession_date_time'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('guestsession_date_time') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.guestLecture.fields.guestsession_date_time_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.guestLecture.fields.guest_session_duration') }}</label>
                            @foreach(App\GuestLecture::GUEST_SESSION_DURATION_RADIO as $key => $label)
                                <div>
                                    <input type="radio" id="guest_session_duration_{{ $key }}" name="guest_session_duration" value="{{ $key }}" {{ old('guest_session_duration', $guestLecture->guest_session_duration) === (string) $key ? 'checked' : '' }} required>
                                    <label for="guest_session_duration_{{ $key }}">{{ $label }}</label>
                                </div>
                            @endforeach
                            @if($errors->has('guest_session_duration'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('guest_session_duration') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.guestLecture.fields.guest_session_duration_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.guestLecture.fields.platform') }}</label>
                            <select class="form-control" name="platform" id="platform" required>
                                <option value disabled {{ old('platform', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\GuestLecture::PLATFORM_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('platform', $guestLecture->platform) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('platform'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('platform') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.guestLecture.fields.platform_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection