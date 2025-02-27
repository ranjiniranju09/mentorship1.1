@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.mentor.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.mentors.index') }}">
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
                <a class="btn btn-default" href="{{ route('admin.mentors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#mentorname_sessions" role="tab" data-toggle="tab">
                {{ trans('cruds.session.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#mentorname_mappings" role="tab" data-toggle="tab">
                {{ trans('cruds.mapping.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="mentorname_sessions">
            @includeIf('admin.mentors.relationships.mentornameSessions', ['sessions' => $mentor->mentornameSessions])
        </div>
        <div class="tab-pane" role="tabpanel" id="mentorname_mappings">
            @includeIf('admin.mentors.relationships.mentornameMappings', ['mappings' => $mentor->mentornameMappings])
        </div>
    </div>
</div>

@endsection