<?php

namespace App\Http\Requests\Vendor\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required',
            'password' => 'required',
            'fcm_token' => 'required',
        ];
    }
}
