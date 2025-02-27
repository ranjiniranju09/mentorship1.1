@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.oneOneAttendance.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.one-one-attendances.update", [$oneOneAttendance->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="session_attendance_id">{{ trans('cruds.oneOneAttendance.fields.session_attendance') }}</label>
                            <select class="form-control select2" name="session_attendance_id" id="session_attendance_id" required>
                                @foreach($session_attendances as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('session_attendance_id') ? old('session_attendance_id') : $oneOneAttendance->session_attendance->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('session_attendance'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('session_attendance') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.oneOneAttendance.fields.session_attendance_helper') }}</span>
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