@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.mentee.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.mentees.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mentee.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $mentee->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mentee.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $mentee->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mentee.fields.email') }}
                                    </th>
                                    <td>
                                        {{ $mentee->email }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mentee.fields.mobile') }}
                                    </th>
                                    <td>
                                        {{ $mentee->mobile }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mentee.fields.dob') }}
                                    </th>
                                    <td>
                                        {{ $mentee->dob }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mentee.fields.skilss') }}
                                    </th>
                                    <td>
                                        {{ $mentee->skilss }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mentee.fields.interestedskills') }}
                                    </th>
                                    <td>
                                        {{ $mentee->interestedskills }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mentee.fields.languagesspoken') }}
                                    </th>
                                    <td>
                                        @foreach($mentee->languagesspokens as $key => $languagesspoken)
                                            <span class="label label-info">{{ $languagesspoken->langname }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.mentees.index') }}">
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