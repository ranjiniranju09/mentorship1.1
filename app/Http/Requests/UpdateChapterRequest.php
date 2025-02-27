<?php

namespace App\Http\Requests;

use App\Chapter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateChapterRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('chapter_edit');
    }

    public function rules()
    {
        return [
            'chaptername' => [
                'string',
                'required',
            ],
            'module_id' => [
                'required',
                'integer',
            ],
            'objective' => [
                'string',
                'required',
            ],
            'description' => [
                'string',
                'required',
            ],
            'published' => [
                'required',
            ],
        ];
    }
}
