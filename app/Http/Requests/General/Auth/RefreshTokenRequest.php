<?php

namespace App\Http\Requests\General\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RefreshTokenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "refresh_token" => 'required'
        ];
    }
}
