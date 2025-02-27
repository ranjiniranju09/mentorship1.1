@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.chapter.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.chapters.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.chapter.fields.id') }}
                        </th>
                        <td>
                            {{ $chapter->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.chapter.fields.chaptername') }}
                        </th>
                        <td>
                            {{ $chapter->chaptername }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.chapter.fields.module') }}
                        </th>
                        <td>
                            {{ $chapter->module->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.chapter.fields.description') }}
                        </th>
                        <td>
                            {{ $chapter->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.chapter.fields.published') }}
                        </th>
                        <td>
                            {{ App\Chapter::PUBLISHED_SELECT[$chapter->published] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.chapters.index') }}">
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
            <a class="nav-link" href="#chapter_chapter_tests" role="tab" data-toggle="tab">
                {{ trans('cruds.chapterTest.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#chapter_subchapters" role="tab" data-toggle="tab">
                {{ trans('cruds.subchapter.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#lesson_create_progress_tables" role="tab" data-toggle="tab">
                {{ trans('cruds.createProgressTable.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#chapterid_moduleresourcebanks" role="tab" data-toggle="tab">
                {{ trans('cruds.moduleresourcebank.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="chapter_chapter_tests">
            @includeIf('admin.chapters.relationships.chapterChapterTests', ['chapterTests' => $chapter->chapterChapterTests])
        </div>
        <div class="tab-pane" role="tabpanel" id="chapter_subchapters">
            @includeIf('admin.chapters.relationships.chapterSubchapters', ['subchapters' => $chapter->chapterSubchapters])
        </div>
        <div class="tab-pane" role="tabpanel" id="lesson_create_progress_tables">
            @includeIf('admin.chapters.relationships.lessonCreateProgressTables', ['createProgressTables' => $chapter->lessonCreateProgressTables])
        </div>
        <div class="tab-pane" role="tabpanel" id="chapterid_moduleresourcebanks">
            @includeIf('admin.chapters.relationships.chapteridModuleresourcebanks', ['moduleresourcebanks' => $chapter->chapteridModuleresourcebanks])
        </div>
    </div>
</div>

@endsection