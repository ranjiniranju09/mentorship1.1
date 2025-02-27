<?php

namespace App\Http\Requests;

use App\TicketDescription;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTicketDescriptionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ticket_description_edit');
    }

    public function rules()
    {
        return [
            'ticket_category_id' => [
                'required',
                'integer',
            ],
            'ticket_description' => [
                'required',
            ],
            'supporting_files' => [
                'array',
            ],
            'supporting_photo' => [
                'array',
            ],
            'ticket_title' => [
                'string',
                'required',
            ],
        ];
    }
}
