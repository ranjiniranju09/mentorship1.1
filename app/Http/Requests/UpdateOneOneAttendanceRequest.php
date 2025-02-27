<?php

namespace App\Http\Requests;

use App\OneOneAttendance;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateOneOneAttendanceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('one_one_attendance_edit');
    }

    public function rules()
    {
        return [
            'session_attendance_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
