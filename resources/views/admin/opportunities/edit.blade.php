@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.opportunity.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.opportunities.update", [$opportunity->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.opportunity.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $opportunity->title) }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opportunity.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="link">{{ trans('cruds.opportunity.fields.link') }}</label>
                <input class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}" type="text" name="link" id="link" value="{{ old('link', $opportunity->link) }}" required>
                @if($errors->has('link'))
                    <div class="invalid-feedback">
                        {{ $errors->first('link') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opportunity.fields.link_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.opportunity.fields.opportunity_type') }}</label>
                <select class="form-control {{ $errors->has('opportunity_type') ? 'is-invalid' : '' }}" name="opportunity_type" id="opportunity_type" required>
                    <option value disabled {{ old('opportunity_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Opportunity::OPPORTUNITY_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('opportunity_type', $opportunity->opportunity_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('opportunity_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('opportunity_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opportunity.fields.opportunity_type_helper') }}</span>
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