<?php

namespace App\Http\Requests;

use App\Mentor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateMentorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mentor_edit');
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
                'unique:mentors,email,' . request()->route('mentor')->id,
            ],
            'mobile' => [
                'required',
                'numeric',
                'min:1000000000',  // Minimum 10-digit number (adjust as needed)
                'max:9223372036854775807', // Maximum BIGINT value
                'unique:mentors,mobile,' . request()->route('mentor')->id,
            ],
            'companyname' => [
                'string',
                'required',
            ],
            'skills' => [
                'string',
                'required',
            ],
            'langspokens.*' => [
                'integer',
            ],
            'langspokens' => [
                'required',
                'array',
            ],
        ];
    }
}
