<?php

namespace App\Http\Requests;

use App\Mentor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreMentorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mentor_create');
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
                'unique:mentors',
            ],
            'mobile' => [
                'required',
                'numeric',
                'digits_between:1,15',
                'unique:mentors,mobile',
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
