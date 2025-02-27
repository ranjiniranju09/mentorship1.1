@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.test.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('testupdate', [$test->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="module_id">{{ trans('cruds.test.fields.module') }}</label>
                <select class="form-control select2 {{ $errors->has('module_id') ? 'is-invalid' : '' }}" name="module_id" id="module_id">
                    <option value="" disabled {{ old('module_id', $test->module_id) == '' ? 'selected' : '' }}>
                        {{ trans('global.pleaseSelect') }}
                    </option>
                    @foreach($modules as $id => $entry)
                        <option value="{{ $id }}" {{ old('module_id', $test->module_id) == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('module_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('module_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.test.fields.course_helper') }}</span>
            </div>
            
            <div class="form-group">
                <label for="chapter_id">{{ trans('cruds.test.fields.chapter') }}</label>
                <select class="form-control select2 {{ $errors->has('chapter_id') ? 'is-invalid' : '' }}" name="chapter_id" id="chapter_id">
                    <option value="" disabled {{ old('chapter_id', $test->chapter_id) == '' ? 'selected' : '' }}>
                        {{ trans('global.pleaseSelect') }}
                    </option>
                    @foreach($chapters as $id => $entry)
                        <option value="{{ $id }}" {{ old('chapter_id', $test->chapter_id) == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('chapter_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('chapter_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.test.fields.lesson_helper') }}</span>
            </div>
            
            <div class="form-group">
                <label for="title">{{ trans('cruds.test.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $test->title) }}">
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.test.fields.title_helper') }}</span>
            </div>
            
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_published') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_published" value="0">
                    <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $test->is_published) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_published">{{ trans('cruds.test.fields.is_published') }}</label>
                </div>
                @if($errors->has('is_published'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_published') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.test.fields.is_published_helper') }}</span>
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
