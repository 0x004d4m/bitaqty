<?php

namespace App\Http\Resources\General;

use App\Models\FieldsAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
{
    protected $order_id;
    protected $product_id;

    public function orderId($value)
    {
        $this->order_id = $value;
        return $this;
    }

    public function productId($value)
    {
        $this->product_id = $value;
        return $this;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $answer = FieldsAnswer::where('field_id', $this->id)->where('order_id', $this->order_id)->where('product_id', $this->product_id)->first();
        return [
            "id" => $this->id,
            "name" => $this->name,
            "field_type" => $this->fieldType->name,
            "is_confirmed" => $this->is_confirmed,
            "answer" => $answer? $answer->answer : '-',
        ];
    }
}
