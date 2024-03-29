<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class ClientAddCreditRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "amount" => 'required',
            "notes" => 'sometimes',
            "client_id" => 'required',
        ];
    }
}
