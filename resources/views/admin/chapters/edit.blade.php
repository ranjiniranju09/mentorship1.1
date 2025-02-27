@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.chapter.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.chapters.update", [$chapter->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="chaptername">{{ trans('cruds.chapter.fields.chaptername') }}</label>
                <input class="form-control {{ $errors->has('chaptername') ? 'is-invalid' : '' }}" type="text" name="chaptername" id="chaptername" value="{{ old('chaptername', $chapter->chaptername) }}" required>
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
                        <option value="{{ $id }}" {{ (old('module_id') ? old('module_id') : $chapter->module->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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
                <label class="required" for="objective">Objective</label>
                <textarea 
                    class="form-control {{ $errors->has('objective') ? 'is-invalid' : '' }}" 
                    name="objective" 
                    id="objective" 
                    rows="4" 
                    >{{ old('objective', $chapter->objective) }}</textarea>
                @if($errors->has('objective'))
                    <div class="invalid-feedback">
                        {{ $errors->first('objective') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="required" for="description">{{ trans('cruds.chapter.fields.description') }}</label>
                <textarea 
                    class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" 
                    name="description" 
                    id="description" 
                    rows="4" 
                    >{{ old('description', $chapter->description) }}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.chapter.fields.description_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="mentorsnote">{{ trans('cruds.chapter.fields.mentorsnote') }}</label>
                <textarea 
                    id="mentorsnote" 
                    name="mentorsnote" 
                    class="form-control {{ $errors->has('mentorsnote') ? 'is-invalid' : '' }}"
                >{{ old('mentorsnote', $chapter->mentorsnote) }}</textarea>
                @if($errors->has('mentorsnote'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mentorsnote') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="required">Published</label>
                <select 
                    class="form-control {{ $errors->has('published') ? 'is-invalid' : '' }}" 
                    name="published" 
                    id="published" 
                    required>
                    <option value="" disabled {{ old('published', $chapter->published) === null ? 'selected' : '' }}>
                        Please select
                    </option>
                    <option value="Yes" {{ old('published', $chapter->published) === 'Yes' ? 'selected' : '' }}>
                        Yes
                    </option>
                    <option value="No" {{ old('published', $chapter->published) === 'No' ? 'selected' : '' }}>
                        No
                    </option>
                </select>
                
                @if($errors->has('published'))
                    <div class="invalid-feedback">
                        {{ $errors->first('published') }}
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