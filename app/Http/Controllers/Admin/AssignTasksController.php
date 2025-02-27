<?php

namespace App\Http\Controllers\Admin;

use App\AssignTask;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAssignTaskRequest;
use App\Http\Requests\StoreAssignTaskRequest;
use App\Http\Requests\UpdateAssignTaskRequest;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AssignTasksController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('assign_task_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AssignTask::query()->select(sprintf('%s.*', (new AssignTask)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'assign_task_show';
                $editGate      = 'assign_task_edit';
                $deleteGate    = 'assign_task_delete';
                $crudRoutePart = 'assign-tasks';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('attachments', function ($row) {
                if (! $row->attachments) {
                    return '';
                }
                $links = [];
                foreach ($row->attachments as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->rawColumns(['actions', 'placeholder', 'attachments']);

            return $table->make(true);
        }

        return view('admin.assignTasks.index');
    }

    public function create()
    {
        abort_if(Gate::denies('assign_task_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.assignTasks.create');
    }

    public function store(StoreAssignTaskRequest $request)
    {

        $assignTask = AssignTask::create($request->all());

        /*
        foreach ($request->input('attachments', []) as $file) {
            $assignTask->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attachments');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $assignTask->id]);
        }*/
        foreach ($request->input('attachments', []) as $file) {
            $assignTask->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attachments', 's3');

            //$media->update(['model_id' => $task->id]); // specify the disk here
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $assignTask->id]);
        }

        return redirect()->route('admin.assign-tasks.index');
    }

    public function edit(AssignTask $assignTask)
    {
        abort_if(Gate::denies('assign_task_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.assignTasks.edit', compact('assignTask'));
    }

    public function update(UpdateAssignTaskRequest $request, AssignTask $assignTask)
    {
        $assignTask->update($request->all());

        if (count($assignTask->attachments) > 0) {
            foreach ($assignTask->attachments as $media) {
                if (! in_array($media->file_name, $request->input('attachments', []))) {
                    $media->delete();
                }
            }
        }
        $media = $assignTask->attachments->pluck('file_name')->toArray();
        foreach ($request->input('attachments', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $assignTask->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attachments');
            }
        }

        return redirect()->route('admin.assign-tasks.index');
    }

    public function show(AssignTask $assignTask)
    {
        abort_if(Gate::denies('assign_task_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.assignTasks.show', compact('assignTask'));
    }

    public function destroy(AssignTask $assignTask)
    {
        abort_if(Gate::denies('assign_task_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $assignTask->delete();

        return back();
    }

    public function massDestroy(MassDestroyAssignTaskRequest $request)
    {
        $assignTasks = AssignTask::find(request('ids'));

        foreach ($assignTasks as $assignTask) {
            $assignTask->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('assign_task_create') && Gate::denies('assign_task_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new AssignTask();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
