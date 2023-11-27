<?php

namespace App\Http\Requests\Vendor;

use App\Models\Client;
use App\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'currency_id' => "sometimes|exists:currencies,id",
        ];
    }
}
