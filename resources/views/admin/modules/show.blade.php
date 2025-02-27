@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.module.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.modules.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.module.fields.id') }}
                        </th>
                        <td>
                            {{ $module->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.module.fields.name') }}
                        </th>
                        <td>
                            {{ $module->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Objective
                        </th>
                        <td>
                            {{ $module->objective }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.module.fields.description') }}
                        </th>
                        <td>
                            {{ $module->description }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.modules.index') }}">
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
            <a class="nav-link" href="#modulename_sessions" role="tab" data-toggle="tab">
                {{ trans('cruds.session.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="modulename_sessions">
            @includeIf('admin.modules.relationships.modulenameSessions', ['sessions' => $module->modulenameSessions])
        </div>
    </div>
</div>

@endsection