<?php

namespace App\Http\Requests;

use App\GuestLecture;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreGuestLectureRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('guest_lecture_create');
    }

    public function rules()
    {
        return [
            'guessionsession_title' => [
                'string',
                'required',
            ],
            'invited_mentees.*' => [
                'integer',
            ],
            'invited_mentees' => [
                'required',
                'array',
            ],
            'guestsession_date_time' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'guest_session_duration' => [
                'required',
            ],
            'platform' => [
                'required',
            ],
            'recording' => [
                'required',
                
            ],
        ];
    }
}
