@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.mentor.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.mentors.update", [$mentor->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.mentor.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $mentor->name) }}" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mentor.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="email">{{ trans('cruds.mentor.fields.email') }}</label>
                            <input class="form-control" type="email" name="email" id="email" value="{{ old('email', $mentor->email) }}" required>
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mentor.fields.email_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="mobile">{{ trans('cruds.mentor.fields.mobile') }}</label>
                            <input class="form-control" type="number" name="mobile" id="mobile" value="{{ old('mobile', $mentor->mobile) }}" step="1" required>
                            @if($errors->has('mobile'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mobile') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mentor.fields.mobile_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="companyname">{{ trans('cruds.mentor.fields.companyname') }}</label>
                            <input class="form-control" type="text" name="companyname" id="companyname" value="{{ old('companyname', $mentor->companyname) }}" required>
                            @if($errors->has('companyname'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('companyname') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mentor.fields.companyname_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="skills">{{ trans('cruds.mentor.fields.skills') }}</label>
                            <input class="form-control" type="text" name="skills" id="skills" value="{{ old('skills', $mentor->skills) }}" required>
                            @if($errors->has('skills'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('skills') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mentor.fields.skills_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="langspokens">{{ trans('cruds.mentor.fields.langspoken') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="langspokens[]" id="langspokens" multiple required>
                                @foreach($langspokens as $id => $langspoken)
                                    <option value="{{ $id }}" {{ (in_array($id, old('langspokens', [])) || $mentor->langspokens->contains($id)) ? 'selected' : '' }}>{{ $langspoken }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('langspokens'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('langspokens') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mentor.fields.langspoken_helper') }}</span>
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