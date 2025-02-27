<?php

namespace App\Http\Requests;

use App\SurveyForm;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSurveyFormRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('survey_form_edit');
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
