@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.guestSessionAttendance.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.guest-session-attendances.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="session_title_id">{{ trans('cruds.guestSessionAttendance.fields.session_title') }}</label>
                <select class="form-control select2 {{ $errors->has('session_title') ? 'is-invalid' : '' }}" name="session_title_id" id="session_title_id" required>
                    @foreach($session_titles as $id => $entry)
                        <option value="{{ $id }}" {{ old('session_title_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('session_title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('session_title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.guestSessionAttendance.fields.session_title_helper') }}</span>
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