<?php

namespace App\Http\Requests;

use App\Mentee;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateMenteeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mentee_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'unique:mentees,email,' . request()->route('mentee')->id,
            ],
            'mobile' => [
                'required',
                'numeric',
                'min:1000000000',  // Minimum 10-digit number (adjust as needed)
                'max:9223372036854775807', // Maximum BIGINT value
            ],

            'dob' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'skills' => [
                'string',
                'nullable',
            ],
            'interestedskills' => [
                'string',
                'required',
            ],
            'languagesspokens.*' => [
                'integer',
            ],
            'languagesspokens' => [
                'array',
            ],
        ];
    }
}
