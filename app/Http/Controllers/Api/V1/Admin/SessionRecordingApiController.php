<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreSessionRecordingRequest;
use App\Http\Requests\UpdateSessionRecordingRequest;
use App\Http\Resources\Admin\SessionRecordingResource;
use App\SessionRecording;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionRecordingApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('session_recording_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SessionRecordingResource(SessionRecording::with(['session_title'])->get());
    }

    public function store(StoreSessionRecordingRequest $request)
    {
        $sessionRecording = SessionRecording::create($request->all());

        foreach ($request->input('session_video_file', []) as $file) {
            $sessionRecording->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('session_video_file');
        }

        return (new SessionRecordingResource($sessionRecording))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SessionRecording $sessionRecording)
    {
        abort_if(Gate::denies('session_recording_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SessionRecordingResource($sessionRecording->load(['session_title']));
    }

    public function update(UpdateSessionRecordingRequest $request, SessionRecording $sessionRecording)
    {
        $sessionRecording->update($request->all());

        if (count($sessionRecording->session_video_file) > 0) {
            foreach ($sessionRecording->session_video_file as $media) {
                if (! in_array($media->file_name, $request->input('session_video_file', []))) {
                    $media->delete();
                }
            }
        }
        $media = $sessionRecording->session_video_file->pluck('file_name')->toArray();
        foreach ($request->input('session_video_file', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $sessionRecording->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('session_video_file');
            }
        }

        return (new SessionRecordingResource($sessionRecording))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SessionRecording $sessionRecording)
    {
        abort_if(Gate::denies('session_recording_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sessionRecording->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
