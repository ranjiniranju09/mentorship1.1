<?php

namespace App\Http\Requests;

use App\Mapping;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyMappingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('mapping_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:mappings,id',
        ];
    }
}
