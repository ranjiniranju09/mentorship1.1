<?php

namespace App\Http\Requests;

use App\GuestSessionAttendance;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyGuestSessionAttendanceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('guest_session_attendance_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:guest_session_attendances,id',
        ];
    }
}
