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
            "name" => "required|min:3",
            "address" => "required|min:3",
            "phone" =>
            [
                'required',
                Rule::unique('clients','phone')->ignore(request('client_id')),
                new ValidPhoneNumber(Client::where('id', request('client_id'))->first()->country_id)
            ],
            "commercial_name" => "required|min:3",
            "email" => ["required"],["email"], Rule::unique('clients','email')->ignore(request('client_id')),
            "country_id" => "required|exists:countries,id",
            'state_id' =>
            [
                'required',
                Rule::exists('states', 'id')->where(function ($query) {
                    $query->where('country_id', Client::where('id', request('client_id'))->first()->country_id);
                })
            ],
            'currency_id' =>
            [
                'required',
                Rule::exists('currencies', 'id')->where(function ($query) {
                    $query->where('country_id', Client::where('id', request('client_id'))->first()->country_id);
                })
            ],
        ];
    }
}
