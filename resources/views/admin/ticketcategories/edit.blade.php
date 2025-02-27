@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.ticketcategory.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.ticketcategories.update", [$ticketcategory->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="category_description">{{ trans('cruds.ticketcategory.fields.category_description') }}</label>
                <input class="form-control {{ $errors->has('category_description') ? 'is-invalid' : '' }}" type="text" name="category_description" id="category_description" value="{{ old('category_description', $ticketcategory->category_description) }}" required>
                @if($errors->has('category_description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('category_description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ticketcategory.fields.category_description_helper') }}</span>
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