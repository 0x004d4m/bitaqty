<?php

namespace App\Http\Requests\Client;

use App\Models\Client;
use App\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => "sometimes|min:3",
            "address" => "sometimes|min:3",
            "phone" =>
            [
                'sometimes',
                Rule::unique('clients','phone')->ignore(request('client_id')),
                new ValidPhoneNumber(Client::where('id', request('client_id'))->first()->country_id)
            ],
            "commercial_name" => "sometimes|min:3",
            "email" => ["sometimes"],["email"], Rule::unique('clients','email')->ignore(request('client_id')),
            "country_id" => "sometimes|exists:countries,id",
            'state_id' =>
            [
                'sometimes',
                Rule::exists('states', 'id')->where(function ($query) {
                    $query->where('country_id', Client::where('id', request('client_id'))->first()->country_id);
                })
            ],
            'currency_id' =>
            [
                'sometimes',
                Rule::exists('currencies', 'id')->where(function ($query) {
                    $query->where('country_id', Client::where('id', request('client_id'))->first()->country_id);
                })
            ],
        ];
    }
}
