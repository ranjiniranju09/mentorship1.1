<?php

namespace App\Http\Requests;

use App\Mentee;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreMenteeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mentee_create');
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
                'unique:mentees',
            ],
            'mobile' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'dob' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'skilss' => [
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
