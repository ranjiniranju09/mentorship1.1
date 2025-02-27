<?php

namespace App\Http\Requests;

use App\TicketDescription;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTicketDescriptionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('ticket_description_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:ticket_descriptions,id',
        ];
    }
}
