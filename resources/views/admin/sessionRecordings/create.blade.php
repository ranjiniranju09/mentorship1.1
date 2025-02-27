@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.sessionRecording.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{route('admin.sessionRecordings.store')}}" >
            @csrf
            
            <div class="form-group">
                <label class="required" for="session_title_id">{{ trans('cruds.sessionRecording.fields.session_title') }}</label>
                <select class="form-control select2 {{ $errors->has('session_title') ? 'is-invalid' : '' }}" name="session_title_id" id="session_title_id" required>
                    <option value="" disabled selected>{{ trans('global.pleaseSelect') }}</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session->id }}" {{ old('session_title_id') == $session->id ? 'selected' : '' }}>
                            {{ $session->session_title }} - {{ $session->mentor_name }} ({{ $session->sessiondatetime }})
                        </option>
                    @endforeach
                </select>
                @if($errors->has('session_title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('session_title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.sessionRecording.fields.session_title_helper') }}</span>
            </div>



            <div class="form-group">
                <label class="required" for="session_video_file"> {{ trans('cruds.sessionRecording.fields.session_video_file') }}</label>
                <input type="file" name="session_video_file" class="form-control" required>
                @if($errors->has('session_video_file'))
                    <div class="invalid-feedback">
                        {{ $errors->first('session_video_file') }}
                    </div>
                @endif
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
