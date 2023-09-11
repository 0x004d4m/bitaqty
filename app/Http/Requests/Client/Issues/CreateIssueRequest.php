<?php

namespace App\Http\Requests\Client\Issues;

use Illuminate\Foundation\Http\FormRequest;

class CreateIssueRequest extends FormRequest
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
