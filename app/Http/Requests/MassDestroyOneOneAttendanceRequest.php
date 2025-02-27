<?php

namespace App\Http\Requests;

use App\OneOneAttendance;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyOneOneAttendanceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('one_one_attendance_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:one_one_attendances,id',
        ];
    }
}
