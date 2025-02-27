@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.mentee.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.mentees.update", [$mentee->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.mentee.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $mentee->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mentee.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.mentee.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $mentee->email) }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mentee.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="mobile">{{ trans('cruds.mentee.fields.mobile') }}</label>
                <input class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}" type="number" name="mobile" id="mobile" value="{{ old('mobile', $mentee->mobile) }}" step="1" required>
                @if($errors->has('mobile'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mobile') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mentee.fields.mobile_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="dob">{{ trans('cruds.mentee.fields.dob') }}</label>
                <input class="form-control date {{ $errors->has('dob') ? 'is-invalid' : '' }}" type="text" name="dob" id="dob" value="{{ old('dob', $mentee->dob) }}">
                @if($errors->has('dob'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dob') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mentee.fields.dob_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="skills">{{ trans('cruds.mentee.fields.skills') }}</label>
                <input class="form-control {{ $errors->has('skills') ? 'is-invalid' : '' }}" type="text" name="skills" id="skills" value="{{ old('skills', $mentee->skills) }}">
                @if($errors->has('skills'))
                    <div class="invalid-feedback">
                        {{ $errors->first('skills') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mentee.fields.skills_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="interestedskills">{{ trans('cruds.mentee.fields.interestedskills') }}</label>
                <input class="form-control {{ $errors->has('interestedskills') ? 'is-invalid' : '' }}" type="text" name="interestedskills" id="interestedskills" value="{{ old('interestedskills', $mentee->interestedskills) }}" required>
                @if($errors->has('interestedskills'))
                    <div class="invalid-feedback">
                        {{ $errors->first('interestedskills') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mentee.fields.interestedskills_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="languagesspokens">{{ trans('cruds.mentee.fields.languagesspoken') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('languagesspokens') ? 'is-invalid' : '' }}" name="languagesspokens[]" id="languagesspokens" multiple>
                    @foreach($languagesspokens as $id => $languagesspoken)
                        <option value="{{ $id }}" {{ (in_array($id, old('languagesspokens', [])) || $mentee->languagesspokens->contains($id)) ? 'selected' : '' }}>{{ $languagesspoken }}</option>
                    @endforeach
                </select>
                @if($errors->has('languagesspokens'))
                    <div class="invalid-feedback">
                        {{ $errors->first('languagesspokens') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mentee.fields.languagesspoken_helper') }}</span>
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