<?php

namespace App\Http\Requests;

use App\Ticketcategory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTicketcategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ticketcategory_edit');
    }

    public function rules()
    {
        return [
            'category_description' => [
                'string',
                'required',
            ],
        ];
    }
}
