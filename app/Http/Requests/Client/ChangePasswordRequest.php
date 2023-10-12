<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "old_password" => "required",
            "new_password" => "required|confirmed|not_in:".request('old_pasword'),
            "remove_all_users_tokens" => "required",
        ];
    }
}
