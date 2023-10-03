<?php

namespace App\Http\Resources\General;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'unavailable_notes' => $this->unavailable_notes,
            'how_to_use' => $this->how_to_use,
            'image' => $this->image,
            'price' => $this->selling_price,
            'stock' => $this->stock,
            'is_vip' => $this->is_vip,
            'fields' => FieldResource::collection($this->fields),
        ];
    }
}
