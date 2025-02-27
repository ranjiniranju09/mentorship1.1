<?php

namespace App\Http\Requests;

use App\SessionRecording;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSessionRecordingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('session_recording_create');
    }

    public function rules()
    {
        return [
            'session_type' => [
                'required',
            ],
            'session_title_id' => [
                'required',
                'integer',
            ],
            'session_video_file' => [
                'array',
                'required',
            ],
            'session_video_file.*' => [
                'required',
            ],
        ];
    }
}
