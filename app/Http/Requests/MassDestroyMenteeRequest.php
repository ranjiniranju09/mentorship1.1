<?php

namespace App\Http\Requests;

use App\Mentee;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyMenteeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('mentee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:mentees,id',
        ];
    }
}
