<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class CreditSendRequset extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount' => 'required',
            'notes' => 'required',
            'to_client_email_or_phone' => 'required',
        ];
    }
}
