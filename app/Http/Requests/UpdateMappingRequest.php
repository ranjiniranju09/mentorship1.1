<?php

namespace App\Http\Requests;

use App\Mapping;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateMappingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mapping_edit');
    }

    public function rules()
    {
        return [
            'menteename_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
