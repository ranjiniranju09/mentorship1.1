@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.moduleresourcebank.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.moduleresourcebanks.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.moduleresourcebank.fields.id') }}
                        </th>
                        <td>
                            {{ $moduleresourcebank->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.moduleresourcebank.fields.module') }}
                        </th>
                        <td>
                            {{ $moduleresourcebank->module->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.moduleresourcebank.fields.chapterid') }}
                        </th>
                        <td>
                            {{ $moduleresourcebank->chapterid->chaptername ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.moduleresourcebank.fields.title') }}
                        </th>
                        <td>
                            {{ $moduleresourcebank->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.moduleresourcebank.fields.link') }}
                        </th>
                        <td>
                            {{ $moduleresourcebank->link }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.moduleresourcebank.fields.descriptivematerial') }}
                        </th>
                        <td>
                            {!! $moduleresourcebank->descriptivematerial !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.moduleresourcebank.fields.resourcefile') }}
                        </th>
                        <td>
                            @foreach($moduleresourcebank->resourcefile as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.moduleresourcebank.fields.resource_photos') }}
                        </th>
                        <td>
                            @foreach($moduleresourcebank->resource_photos as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.moduleresourcebanks.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection