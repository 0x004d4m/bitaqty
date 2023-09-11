<?php

namespace App\Http\Resources\General;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class IssueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'image' => $this->image,
            'solution' => $this->solution,
            'is_solved' => $this->is_solved,
            'is_duplicate' => $this->is_duplicate,
            'issue_type' => new IssueTypeResource($this->issueType),
        ];
    }
}
