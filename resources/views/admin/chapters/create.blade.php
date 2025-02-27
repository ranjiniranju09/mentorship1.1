@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.chapter.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("chapterstore") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="chaptername">{{ trans('cruds.chapter.fields.chaptername') }}</label>
                <input class="form-control {{ $errors->has('chaptername') ? 'is-invalid' : '' }}" type="text" name="chaptername" id="chaptername" value="{{ old('chaptername', '') }}" required>
                @if($errors->has('chaptername'))
                    <div class="invalid-feedback">
                        {{ $errors->first('chaptername') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.chapter.fields.chaptername_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="module_id">{{ trans('cruds.chapter.fields.module') }}</label>
                <select class="form-control select2 {{ $errors->has('module') ? 'is-invalid' : '' }}" name="module_id" id="module_id" required>
                    @foreach($modules as $id => $entry)
                        <option value="{{ $id }}" {{ old('module_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('module'))
                    <div class="invalid-feedback">
                        {{ $errors->first('module') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.chapter.fields.module_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="description">{{ trans('cruds.chapter.fields.description') }}</label>
                <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description" id="description" value="{{ old('description', '') }}" required>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.chapter.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="objective">Objective</label>
                <input class="form-control {{ $errors->has('objective') ? 'is-invalid' : '' }}" type="text" name="objective" id="objective" required>
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
            <!-- <div class="form-group">
                <label class="required">{{ trans('cruds.chapter.fields.published') }}</label>
                <select class="form-control {{ $errors->has('published') ? 'is-invalid' : '' }}" name="published" id="published" required>
                    <option value disabled {{ old('published', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Chapter::PUBLISHED_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('published', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('published'))
                    <div class="invalid-feedback">
                        {{ $errors->first('published') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.chapter.fields.published_helper') }}</span>
            </div> -->
            <div class="form-group">
                <label class="required" >Published</label>
                <select class="form-control {{ $errors->has('published') ? 'is-invalid' : '' }}" name="published" id="[published]" required>
                    <option value="" disabled {{ old('published') === null ? 'selected' : '' }}>Please select</option>
                    <option value="yes" {{ old('published') === 'Yes' ? 'selected' : '' }}> Yes </option>
                    <option value="no" {{ old('published') === 'No' ? 'selected' : '' }}>No</option>
                </select>
                
                @if($errors->has('your_select_field'))
                    <div class="invalid-feedback">
                        {{ $errors->first('your_select_field') }}
                    </div>
                @endif
                
                <!-- <span class="help-block">Please choose an option from the list.</span> -->
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