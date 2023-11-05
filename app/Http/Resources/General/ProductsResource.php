<?php

namespace App\Http\Resources\General;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $limit = $this->stock;
        if ($this->stock_limit > 0) {
            if ($this->stock > $this->stock_limit) {
                $limit = $this->stock_limit;
            }
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'price' => $this->selling_price,
            'stock' => $limit,
            'is_vip' => $this->is_vip,
        ];
    }
}
