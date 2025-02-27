@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.languagespoken.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.languagespokens.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="langname">{{ trans('cruds.languagespoken.fields.langname') }}</label>
                            <input class="form-control" type="text" name="langname" id="langname" value="{{ old('langname', '') }}" required>
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

        </div>
    </div>
</div>
@endsection