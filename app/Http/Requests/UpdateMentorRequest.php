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
                'integer',
                'min:-2147483648',
                'max:2147483647',
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
