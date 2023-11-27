<?php

namespace App\Http\Requests\General;

use Illuminate\Foundation\Http\FormRequest;

class CreditPrepaidRequset extends FormRequest
{
    public function rules(): array
    {
        return [
            'number' => 'required',
        ];
    }
}
