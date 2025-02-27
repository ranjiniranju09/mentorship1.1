<?php

namespace App\Http\Requests;

use App\SurveyForm;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySurveyFormRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('survey_form_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:survey_forms,id',
        ];
    }
}
