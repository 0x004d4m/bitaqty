<?php

namespace App\Http\Requests\General;

use Illuminate\Foundation\Http\FormRequest;

class IssueRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'description'=>'required',
            'issue_type_id'=>'required|exists:issue_types,id',
            'image'=>'sometimes',
        ];
    }
}
