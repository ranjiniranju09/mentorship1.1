@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.module.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('modulestore') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.module.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.module.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="objective">Objective</label>
                <input class="form-control " type="text" name="objective" id="objective"  required>
                @if($errors->has('objective'))
                    <div class="invalid-feedback">
                        {{ $errors->first('objective') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label class="required" for="mentorsnote">Mentor's Note</label>
                <input class="form-control " type="text" name="mentorsnote" id="mentorsnote"  required>
                @if($errors->has('mentorsnote'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mentorsnote') }}
                    </div>
                @endif
                <!-- <span class="help-block">{{ trans('cruds.module.fields.objective_helper') }}</span> -->
            </div>
            
            <div class="form-group">
                <label class="required" for="description">{{ trans('cruds.module.fields.description') }}</label>
                <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description" id="description" value="{{ old('description', '') }}" required>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.module.fields.description_helper') }}</span>
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