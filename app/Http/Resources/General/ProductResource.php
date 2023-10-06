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
            'suggested_price' => $this->suggested_price,
            'price' => $this->selling_price,
            'stock' => $this->stock,
            'is_vip' => $this->is_vip,
            'type' => new TypeResource($this->category->type),
            'category' => new CategoryResource($this->category),
            'subcategory' => new SubcategoryResource($this->subcategory),
            'fields' => FieldResource::collection($this->subcategory->fields),
        ];
    }
}
