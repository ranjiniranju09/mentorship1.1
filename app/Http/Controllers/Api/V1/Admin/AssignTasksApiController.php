<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\AssignTask;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreAssignTaskRequest;
use App\Http\Requests\UpdateAssignTaskRequest;
use App\Http\Resources\Admin\AssignTaskResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AssignTasksApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('assign_task_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AssignTaskResource(AssignTask::all());
    }

    public function store(StoreAssignTaskRequest $request)
    {
        $assignTask = AssignTask::create($request->all());

        foreach ($request->input('attachments', []) as $file) {
            $assignTask->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attachments');
        }

        return (new AssignTaskResource($assignTask))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AssignTask $assignTask)
    {
        abort_if(Gate::denies('assign_task_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AssignTaskResource($assignTask);
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

        return (new AssignTaskResource($assignTask))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(AssignTask $assignTask)
    {
        abort_if(Gate::denies('assign_task_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $assignTask->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
