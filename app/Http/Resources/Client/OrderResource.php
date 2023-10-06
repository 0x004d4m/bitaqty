<?php

namespace App\Http\Resources\Client;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\General\CategoryResource;
use App\Http\Resources\General\OrderStatusResource;
use App\Http\Resources\General\ProductResource;
use App\Http\Resources\General\ProductsResource;
use App\Http\Resources\General\SubcategoryResource;
use App\Http\Resources\General\TypeResource;
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
            "price" => $this->price,
            "device_name" => $this->device_name,
            "created_at" => $this->created_at,
            "type" => new TypeResource($this->type),
            "category" => new CategoryResource($this->product),
            "subcategory" => new SubcategoryResource($this->product),
            "product" => new ProductResource($this->product, $this->id),
            "order_status" => new OrderStatusResource($this->orderStatus),
        ];
    }
}
