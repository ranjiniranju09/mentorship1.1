@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.testResult.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.test-results.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="test_id">{{ trans('cruds.testResult.fields.test') }}</label>
                            <select class="form-control select2" name="test_id" id="test_id" required>
                                @foreach($tests as $id => $entry)
                                    <option value="{{ $id }}" {{ old('test_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('test'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('test') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.testResult.fields.test_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="student_id">{{ trans('cruds.testResult.fields.student') }}</label>
                            <select class="form-control select2" name="student_id" id="student_id" required>
                                @foreach($students as $id => $entry)
                                    <option value="{{ $id }}" {{ old('student_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('student'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('student') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.testResult.fields.student_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="score">{{ trans('cruds.testResult.fields.score') }}</label>
                            <input class="form-control" type="number" name="score" id="score" value="{{ old('score', '') }}" step="1">
                            @if($errors->has('score'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('score') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.testResult.fields.score_helper') }}</span>
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