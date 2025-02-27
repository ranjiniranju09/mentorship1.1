<?php

namespace App\Http\Requests;

use App\TicketResponse;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTicketResponseRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ticket_response_create');
    }

    public function rules()
    {
        return [
            'ticket_description_id' => [
                'required',
                'integer',
            ],
            'ticket_response' => [
                'required',
            ],
            'supporting_files' => [
                'array',
            ],
            'supporting_photo' => [
                'array',
            ],
        ];
    }
}
