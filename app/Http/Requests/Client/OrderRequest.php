<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "quantity" => "required",
            "device_name" => "required",
            "product_id" => "required",
            "fields" => "required",
        ];
    }
}
