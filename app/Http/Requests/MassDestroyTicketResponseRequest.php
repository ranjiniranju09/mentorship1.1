<?php

namespace App\Http\Requests;

use App\TicketResponse;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTicketResponseRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('ticket_response_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:ticket_responses,id',
        ];
    }
}
