<?php

namespace App\Http\Requests\General\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'forget_token' => 'required',
            'password' => 'required|confirmed',
        ];
    }
}
