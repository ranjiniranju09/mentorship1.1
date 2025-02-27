@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.questionOption.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.question-options.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="question_id">{{ trans('cruds.questionOption.fields.question') }}</label>
                            <select class="form-control select2" name="question_id" id="question_id">
                                @foreach($questions as $id => $entry)
                                    <option value="{{ $id }}" {{ old('question_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('question'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('question') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.questionOption.fields.question_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="option_text">{{ trans('cruds.questionOption.fields.option_text') }}</label>
                            <input class="form-control" type="text" name="option_text" id="option_text" value="{{ old('option_text', '') }}" required>
                            @if($errors->has('option_text'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('option_text') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.questionOption.fields.option_text_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="is_correct" value="0">
                                <input type="checkbox" name="is_correct" id="is_correct" value="1" {{ old('is_correct', 0) == 1 ? 'checked' : '' }}>
                                <label for="is_correct">{{ trans('cruds.questionOption.fields.is_correct') }}</label>
                            </div>
                            @if($errors->has('is_correct'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('is_correct') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.questionOption.fields.is_correct_helper') }}</span>
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