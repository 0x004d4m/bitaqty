<?php

namespace App\Http\Resources\Client;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\General\OrderStatusResource;
use App\Http\Resources\General\ProductsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            "id" => $this->id,
            "category" => $this->product->category->name,
            "subcategory" => $this->product->subcategory->name,
            "image" => $this->product->category->image,
            "price" => $this->price,
            "device_name" => $this->device_name,
            "created_at" => $this->created_at,
            "order_status" => new ProductRequest($this->product),
            "order_status" => new OrderStatusResource($this->orderStatus),
        ];
    }
}
