@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.skill.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.skills.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="skillname">{{ trans('cruds.skill.fields.skillname') }}</label>
                <input class="form-control {{ $errors->has('skillname') ? 'is-invalid' : '' }}" type="text" name="skillname" id="skillname" value="{{ old('skillname', '') }}" required>
                @if($errors->has('skillname'))
                    <div class="invalid-feedback">
                        {{ $errors->first('skillname') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.skill.fields.skillname_helper') }}</span>
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