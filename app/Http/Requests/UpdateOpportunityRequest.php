<?php

namespace App\Http\Requests;

use App\Opportunity;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateOpportunityRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('opportunity_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
            'link' => [
                'string',
                'required',
            ],
            'opportunity_type' => [
                'required',
            ],
        ];
    }
}
