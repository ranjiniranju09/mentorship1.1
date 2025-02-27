<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySessionRecordingRequest;
use App\Http\Requests\StoreSessionRecordingRequest;
use App\Http\Requests\UpdateSessionRecordingRequest;
use App\Session;
use App\SessionRecording;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class SessionRecordingController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('session_recording_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sessionRecordings = SessionRecording::with(['session_title', 'media'])->get();

        $sessions = Session::get();

        return view('frontend.sessionRecordings.index', compact('sessionRecordings', 'sessions'));
    }

    public function create()
    {
        abort_if(Gate::denies('session_recording_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $session_titles = Session::pluck('session_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.sessionRecordings.create', compact('session_titles'));
    }

    public function store(StoreSessionRecordingRequest $request)
    {
        $sessionRecording = SessionRecording::create($request->all());

        foreach ($request->input('session_video_file', []) as $file) {
            $sessionRecording->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('session_video_file');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $sessionRecording->id]);
        }

        return redirect()->route('frontend.session-recordings.index');
    }

    public function edit(SessionRecording $sessionRecording)
    {
        abort_if(Gate::denies('session_recording_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $session_titles = Session::pluck('session_title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sessionRecording->load('session_title');

        return view('frontend.sessionRecordings.edit', compact('sessionRecording', 'session_titles'));
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

        return redirect()->route('frontend.session-recordings.index');
    }

    public function show(SessionRecording $sessionRecording)
    {
        abort_if(Gate::denies('session_recording_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sessionRecording->load('session_title');

        return view('frontend.sessionRecordings.show', compact('sessionRecording'));
    }

    public function destroy(SessionRecording $sessionRecording)
    {
        abort_if(Gate::denies('session_recording_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sessionRecording->delete();

        return back();
    }

    public function massDestroy(MassDestroySessionRecordingRequest $request)
    {
        $sessionRecordings = SessionRecording::find(request('ids'));

        foreach ($sessionRecordings as $sessionRecording) {
            $sessionRecording->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('session_recording_create') && Gate::denies('session_recording_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new SessionRecording();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
