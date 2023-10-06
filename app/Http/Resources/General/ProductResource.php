<?php

namespace App\Http\Resources\General;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\SubcategoryRequest;
use App\Http\Requests\TypeRequest;
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
            'type' => TypeRequest::collection($this->type),
            'category' => CategoryRequest::collection($this->category),
            'subcategory' => SubcategoryRequest::collection($this->subcategory),
            'fields' => FieldResource::collection($this->subcategory->fields),
        ];
    }
}
