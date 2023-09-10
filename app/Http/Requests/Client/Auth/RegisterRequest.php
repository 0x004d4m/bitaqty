<?php

namespace App\Http\Requests\Client\Auth;

use App\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        Log::debug($this->input());
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:clients,email',
            'phone' => ['required', 'unique:clients,phone', new ValidPhoneNumber($this->input('country_id'))],
            'password' => 'required|confirmed',
            'address' => 'required|min:3',
            'commercial_name' => 'required|min:3',
            'fcm_token' => 'required',
            'country_id' => 'required|exists:countries,id',
            'state_id' => [
                'required',
                Rule::exists('states', 'id')->where(function ($query) {
                    $query->where('country_id', $this->input('country_id'));
                })
            ],
            'currency_id' => [
                'required',
                Rule::exists('currencies', 'id')->where(function ($query) {
                    $query->where('country_id', $this->input('country_id'));
                })
            ],
        ];
    }
}
