@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.skill.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.skills.update", [$skill->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="skillname">{{ trans('cruds.skill.fields.skillname') }}</label>
                            <input class="form-control" type="text" name="skillname" id="skillname" value="{{ old('skillname', $skill->skillname) }}" required>
                            @if($errors->has('skillname'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('skillname') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.skill.fields.skillname_helper') }}</span>
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