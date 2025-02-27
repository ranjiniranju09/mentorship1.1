@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.createProgressTable.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.create-progress-tables.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.createProgressTable.fields.id') }}
                        </th>
                        <td>
                            {{ $createProgressTable->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.createProgressTable.fields.user') }}
                        </th>
                        <td>
                            {{ $createProgressTable->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.createProgressTable.fields.lesson') }}
                        </th>
                        <td>
                            {{ $createProgressTable->lesson->chaptername ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.createProgressTable.fields.status') }}
                        </th>
                        <td>
                            {{ App\CreateProgressTable::STATUS_SELECT[$createProgressTable->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.create-progress-tables.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection