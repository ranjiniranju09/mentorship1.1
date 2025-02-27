@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.session.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.sessions.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="modulename_id">{{ trans('cruds.session.fields.modulename') }}</label>
                <select class="form-control select2 {{ $errors->has('modulename') ? 'is-invalid' : '' }}" name="modulename_id" id="modulename_id" required>
                    @foreach($modulenames as $id => $entry)
                        <option value="{{ $id }}" {{ old('modulename_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('modulename'))
                    <div class="invalid-feedback">
                        {{ $errors->first('modulename') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.session.fields.modulename_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="mentorname_id">{{ trans('cruds.session.fields.mentorname') }}</label>
                <select class="form-control select2 {{ $errors->has('mentorname') ? 'is-invalid' : '' }}" name="mentorname_id" id="mentorname_id" required>
                    @foreach($mentornames as $id => $entry)
                        <option value="{{ $id }}" {{ old('mentorname_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('mentorname'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mentorname') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.session.fields.mentorname_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="menteename_id">{{ trans('cruds.session.fields.menteename') }}</label>
                <select class="form-control select2 {{ $errors->has('menteename') ? 'is-invalid' : '' }}" name="menteename_id" id="menteename_id" required>
                    @foreach($menteenames as $id => $entry)
                        <option value="{{ $id }}" {{ old('menteename_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('menteename'))
                    <div class="invalid-feedback">
                        {{ $errors->first('menteename') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.session.fields.menteename_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="sessiondatetime">{{ trans('cruds.session.fields.sessiondatetime') }}</label>
                <input class="form-control datetime {{ $errors->has('sessiondatetime') ? 'is-invalid' : '' }}" type="text" name="sessiondatetime" id="sessiondatetime" value="{{ old('sessiondatetime') }}" required>
                @if($errors->has('sessiondatetime'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sessiondatetime') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.session.fields.sessiondatetime_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="sessionlink">{{ trans('cruds.session.fields.sessionlink') }}</label>
                <input class="form-control {{ $errors->has('sessionlink') ? 'is-invalid' : '' }}" type="text" name="sessionlink" id="sessionlink" value="{{ old('sessionlink', '') }}" required>
                @if($errors->has('sessionlink'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sessionlink') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.session.fields.sessionlink_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="session_title">{{ trans('cruds.session.fields.session_title') }}</label>
                <input class="form-control {{ $errors->has('session_title') ? 'is-invalid' : '' }}" type="text" name="session_title" id="session_title" value="{{ old('session_title', '') }}" required>
                @if($errors->has('session_title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('session_title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.session.fields.session_title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.session.fields.session_duration_minutes') }}</label>
                @foreach(App\Session::SESSION_DURATION_MINUTES_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('session_duration_minutes') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="session_duration_minutes_{{ $key }}" name="session_duration_minutes" value="{{ $key }}" {{ old('session_duration_minutes', '') === (string) $key ? 'checked' : '' }} required>
                        <label class="form-check-label" for="session_duration_minutes_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('session_duration_minutes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('session_duration_minutes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.session.fields.session_duration_minutes_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection