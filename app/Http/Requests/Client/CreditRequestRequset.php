<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class CreditRequestRequset extends FormRequest
{
    public function rules(): array
    {
        return [
            "image" => 'required',
            "amount" => 'required',
            "notes" => 'required',
            "deposit_or_withdraw" => 'required',
            "supported_account_id" => 'required',
        ];
    }
}
