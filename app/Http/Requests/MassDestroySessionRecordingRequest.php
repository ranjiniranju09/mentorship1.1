<?php

namespace App\Http\Requests;

use App\SessionRecording;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySessionRecordingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('session_recording_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:session_recordings,id',
        ];
    }
}
