<?php

namespace App\Http\Requests\Vendor\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token' => 'required',
            'password' => 'required|confirmed',
        ];
    }
}
