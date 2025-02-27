<?php

namespace App\Http\Requests;

use App\Subchapter;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSubchapterRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('subchapter_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
            'content' => [
                'required',
            ],
            'chapter_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
