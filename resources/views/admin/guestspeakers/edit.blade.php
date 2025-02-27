@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.guestspeaker.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.guestspeakers.update", [$guestspeaker->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="speakername">{{ trans('cruds.guestspeaker.fields.speakername') }}</label>
                <input class="form-control {{ $errors->has('speakername') ? 'is-invalid' : '' }}" type="text" name="speakername" id="speakername" value="{{ old('speakername', $guestspeaker->speakername) }}" required>
                @if($errors->has('speakername'))
                    <div class="invalid-feedback">
                        {{ $errors->first('speakername') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.guestspeaker.fields.speakername_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="speaker_name">{{ trans('cruds.guestspeaker.fields.speaker_name') }}</label>
                <input class="form-control {{ $errors->has('speaker_name') ? 'is-invalid' : '' }}" type="email" name="speaker_name" id="speaker_name" value="{{ old('speaker_name', $guestspeaker->speaker_name) }}" required>
                @if($errors->has('speaker_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('speaker_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.guestspeaker.fields.speaker_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="speakermobile">{{ trans('cruds.guestspeaker.fields.speakermobile') }}</label>
                <input class="form-control {{ $errors->has('speakermobile') ? 'is-invalid' : '' }}" type="number" name="speakermobile" id="speakermobile" value="{{ old('speakermobile', $guestspeaker->speakermobile) }}" step="1" required>
                @if($errors->has('speakermobile'))
                    <div class="invalid-feedback">
                        {{ $errors->first('speakermobile') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.guestspeaker.fields.speakermobile_helper') }}</span>
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