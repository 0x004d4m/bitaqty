<?php

namespace App\Http\Resources\General;

use App\Models\FieldsAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $answer = FieldsAnswer::where('field_id', $this->id)->where('order_id', $request->get('field_resource_order_id')??0)->where('product_id', $request->get('field_resource_product_id')??0)->first();
        return [
            "id" => $this->id,
            "name" => $this->name,
            "field_type" => $this->fieldType->name,
            "is_confirmed" => $this->is_confirmed,
            "answer" => $answer? $answer->answer : '-',
        ];
    }
}
