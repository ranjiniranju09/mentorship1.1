<?php

namespace App\Http\Requests;

use App\Languagespoken;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateLanguagespokenRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('languagespoken_edit');
    }

    public function rules()
    {
        return [
            'langname' => [
                'string',
                'required',
            ],
        ];
    }
}
