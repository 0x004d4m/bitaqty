<?php

namespace App\Http\Requests\General\Auth;

use Illuminate\Foundation\Http\FormRequest;

class OtpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'otp_token' => 'required',
            'code' => 'required',
        ];
    }
}
