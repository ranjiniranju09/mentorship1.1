@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.languagespoken.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.languagespokens.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="langname">{{ trans('cruds.languagespoken.fields.langname') }}</label>
                <input class="form-control {{ $errors->has('langname') ? 'is-invalid' : '' }}" type="text" name="langname" id="langname" value="{{ old('langname', '') }}" required>
                @if($errors->has('langname'))
                    <div class="invalid-feedback">
                        {{ $errors->first('langname') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.languagespoken.fields.langname_helper') }}</span>
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