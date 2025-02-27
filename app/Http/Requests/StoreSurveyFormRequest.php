<?php

namespace App\Http\Requests;

use App\SurveyForm;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSurveyFormRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('survey_form_create');
    }

    public function rules()
    {
        return [
            'surveytopic' => [
                'string',
                'required',
            ],
        ];
    }
}
