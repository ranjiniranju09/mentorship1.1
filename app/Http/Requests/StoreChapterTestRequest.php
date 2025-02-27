<?php

namespace App\Http\Requests;

use App\ChapterTest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreChapterTestRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('chapter_test_create');
    }

    public function rules()
    {
        return [
            'moduleid_id' => [
                'required',
                'integer',
            ],
            'chapter_id' => [
                'required',
                'integer',
            ],
            'test_title' => [
                'string',
                'required',
            ],
        ];
    }
}
