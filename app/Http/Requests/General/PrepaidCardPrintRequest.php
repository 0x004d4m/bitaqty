<?php

namespace App\Http\Requests\General;

use Illuminate\Foundation\Http\FormRequest;

class PrepaidCardPrintRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "card_ids" => 'required'
        ];
    }
}
