<?php

namespace App\Http\Requests;

use App\Module;
use Illuminate\Support\Facades\Gate;use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateModuleRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('module_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'objective' => [
                'string',
                'required',
            ],
            'description' => [
                'string',
                'required',
            ],
        ];
    }
}
