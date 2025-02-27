@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.mentee.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.mentees.index') }}">
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
                            {{ trans('cruds.mentee.fields.skills') }}
                        </th>
                        <td>
                            {{ $mentee->skills }}
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
                <a class="btn btn-default" href="{{ route('admin.mentees.index') }}">
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
            <a class="nav-link" href="#menteename_sessions" role="tab" data-toggle="tab">
                {{ trans('cruds.session.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#menteename_mappings" role="tab" data-toggle="tab">
                {{ trans('cruds.mapping.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#invited_mentees_guest_lectures" role="tab" data-toggle="tab">
                {{ trans('cruds.guestLecture.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="menteename_sessions">
            @includeIf('admin.mentees.relationships.menteenameSessions', ['sessions' => $mentee->menteenameSessions])
        </div>
        <div class="tab-pane" role="tabpanel" id="menteename_mappings">
            @includeIf('admin.mentees.relationships.menteenameMappings', ['mappings' => $mentee->menteenameMappings])
        </div>
        <div class="tab-pane" role="tabpanel" id="invited_mentees_guest_lectures">
            @includeIf('admin.mentees.relationships.invitedMenteesGuestLectures', ['guestLectures' => $mentee->invitedMenteesGuestLectures])
        </div>
    </div>
</div>

@endsection