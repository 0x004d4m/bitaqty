<?php

namespace App\Http\Requests\General;

use Illuminate\Foundation\Http\FormRequest;

class CreditRequestRequset extends FormRequest
{
    public function rules(): array
    {
        return [
            "image" => 'required',
            "amount" => 'required',
            "notes" => 'required',
            "supported_account_id" => 'required',
        ];
    }
}
