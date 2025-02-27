<?php

namespace App\Http\Requests;

use App\GuestSessionAttendance;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateGuestSessionAttendanceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('guest_session_attendance_edit');
    }

    public function rules()
    {
        return [
            'session_title_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
