<?php

namespace App\Http\Requests;

use App\Session;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSessionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('session_edit');
    }

    public function rules()
    {
        return [
            'modulename_id' => [
                'required',
                'integer',
            ],
            'mentorname_id' => [
                'required',
                'integer',
            ],
            'menteename_id' => [
                'required',
                'integer',
            ],
            'sessiondatetime' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'sessionlink' => [
                'string',
                'required',
            ],
            'session_title' => [
                'string',
                'required',
            ],
            'session_duration_minutes' => [
                'required',
            ],
        ];
    }
}
