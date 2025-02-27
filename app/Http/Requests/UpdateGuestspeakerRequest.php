<?php

namespace App\Http\Requests;

use App\Guestspeaker;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateGuestspeakerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('guestspeaker_edit');
    }

    public function rules()
    {
        return [
            'speakername' => [
                'string',
                'required',
            ],
            'speaker_name' => [
                'required',
            ],
            'speakermobile' => [
                'required',
                'digits_between:10,15',
                'regex:/^[0-9]+$/',
            ],
        ];
    }
}
