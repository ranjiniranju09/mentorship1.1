<?php

namespace App\Http\Requests;

use App\CreateProgressTable;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCreateProgressTableRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('create_progress_table_create');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'lesson_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
