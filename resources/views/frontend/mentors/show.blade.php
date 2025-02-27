@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.mentor.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.mentors.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mentor.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $mentor->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mentor.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $mentor->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mentor.fields.email') }}
                                    </th>
                                    <td>
                                        {{ $mentor->email }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mentor.fields.mobile') }}
                                    </th>
                                    <td>
                                        {{ $mentor->mobile }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mentor.fields.companyname') }}
                                    </th>
                                    <td>
                                        {{ $mentor->companyname }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mentor.fields.skills') }}
                                    </th>
                                    <td>
                                        {{ $mentor->skills }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mentor.fields.langspoken') }}
                                    </th>
                                    <td>
                                        @foreach($mentor->langspokens as $key => $langspoken)
                                            <span class="label label-info">{{ $langspoken->langname }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.mentors.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection