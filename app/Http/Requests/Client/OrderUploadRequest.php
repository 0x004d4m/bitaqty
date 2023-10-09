<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class OrderUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => 'required'
        ];
    }
}
