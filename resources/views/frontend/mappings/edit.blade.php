@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.mapping.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.mappings.update", [$mapping->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="mentorname_id">{{ trans('cruds.mapping.fields.mentorname') }}</label>
                            <select class="form-control select2" name="mentorname_id" id="mentorname_id">
                                @foreach($mentornames as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('mentorname_id') ? old('mentorname_id') : $mapping->mentorname->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('mentorname'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mentorname') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mapping.fields.mentorname_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="menteename_id">{{ trans('cruds.mapping.fields.menteename') }}</label>
                            <select class="form-control select2" name="menteename_id" id="menteename_id" required>
                                @foreach($menteenames as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('menteename_id') ? old('menteename_id') : $mapping->menteename->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('menteename'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('menteename') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mapping.fields.menteename_helper') }}</span>
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