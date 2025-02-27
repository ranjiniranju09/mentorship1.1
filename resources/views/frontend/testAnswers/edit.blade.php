@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.testAnswer.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.test-answers.update", [$testAnswer->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="test_result_id">{{ trans('cruds.testAnswer.fields.test_result') }}</label>
                            <select class="form-control select2" name="test_result_id" id="test_result_id" required>
                                @foreach($test_results as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('test_result_id') ? old('test_result_id') : $testAnswer->test_result->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('test_result'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('test_result') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.testAnswer.fields.test_result_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="question_id">{{ trans('cruds.testAnswer.fields.question') }}</label>
                            <select class="form-control select2" name="question_id" id="question_id" required>
                                @foreach($questions as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('question_id') ? old('question_id') : $testAnswer->question->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('question'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('question') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.testAnswer.fields.question_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="option_id">{{ trans('cruds.testAnswer.fields.option') }}</label>
                            <select class="form-control select2" name="option_id" id="option_id" required>
                                @foreach($options as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('option_id') ? old('option_id') : $testAnswer->option->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('option'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('option') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.testAnswer.fields.option_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="is_correct" value="0">
                                <input type="checkbox" name="is_correct" id="is_correct" value="1" {{ $testAnswer->is_correct || old('is_correct', 0) === 1 ? 'checked' : '' }}>
                                <label for="is_correct">{{ trans('cruds.testAnswer.fields.is_correct') }}</label>
                            </div>
                            @if($errors->has('is_correct'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('is_correct') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.testAnswer.fields.is_correct_helper') }}</span>
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