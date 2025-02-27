@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.chapterTest.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.chapter-tests.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="moduleid_id">{{ trans('cruds.chapterTest.fields.moduleid') }}</label>
                <select class="form-control select2 {{ $errors->has('moduleid') ? 'is-invalid' : '' }}" name="moduleid_id" id="moduleid_id" required>
                    @foreach($moduleids as $id => $entry)
                        <option value="{{ $id }}" {{ old('moduleid_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('moduleid'))
                    <div class="invalid-feedback">
                        {{ $errors->first('moduleid') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.chapterTest.fields.moduleid_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="chapter_id">{{ trans('cruds.chapterTest.fields.chapter') }}</label>
                <select class="form-control select2 {{ $errors->has('chapter') ? 'is-invalid' : '' }}" name="chapter_id" id="chapter_id" required>
                    @foreach($chapters as $id => $entry)
                        <option value="{{ $id }}" {{ old('chapter_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('chapter'))
                    <div class="invalid-feedback">
                        {{ $errors->first('chapter') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.chapterTest.fields.chapter_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="test_title">{{ trans('cruds.chapterTest.fields.test_title') }}</label>
                <input class="form-control {{ $errors->has('test_title') ? 'is-invalid' : '' }}" type="text" name="test_title" id="test_title" value="{{ old('test_title', '') }}" required>
                @if($errors->has('test_title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('test_title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.chapterTest.fields.test_title_helper') }}</span>
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