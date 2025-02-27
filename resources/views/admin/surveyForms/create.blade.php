@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.surveyForm.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.survey-forms.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="surveytopic">{{ trans('cruds.surveyForm.fields.surveytopic') }}</label>
                <input class="form-control {{ $errors->has('surveytopic') ? 'is-invalid' : '' }}" type="text" name="surveytopic" id="surveytopic" value="{{ old('surveytopic', '') }}" required>
                @if($errors->has('surveytopic'))
                    <div class="invalid-feedback">
                        {{ $errors->first('surveytopic') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.surveyForm.fields.surveytopic_helper') }}</span>
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