@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.languagespoken.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.languagespokens.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.languagespoken.fields.id') }}
                        </th>
                        <td>
                            {{ $languagespoken->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.languagespoken.fields.langname') }}
                        </th>
                        <td>
                            {{ $languagespoken->langname }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.languagespokens.index') }}">
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
            <a class="nav-link" href="#langspoken_mentors" role="tab" data-toggle="tab">
                {{ trans('cruds.mentor.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#languagesspoken_mentees" role="tab" data-toggle="tab">
                {{ trans('cruds.mentee.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="langspoken_mentors">
            @includeIf('admin.languagespokens.relationships.langspokenMentors', ['mentors' => $languagespoken->langspokenMentors])
        </div>
        <div class="tab-pane" role="tabpanel" id="languagesspoken_mentees">
            @includeIf('admin.languagespokens.relationships.languagesspokenMentees', ['mentees' => $languagespoken->languagesspokenMentees])
        </div>
    </div>
</div>

@endsection