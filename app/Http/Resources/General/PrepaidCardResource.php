<?php

namespace App\Http\Resources\General;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrepaidCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "serial1"=> $this->prepaidCardStock->serial1,
            "serial2"=> $this->prepaidCardStock->serial2,
            "number1"=> $this->prepaidCardStock->number1,
            "number2"=> $this->prepaidCardStock->number2,
            "cvc"=> $this->prepaidCardStock->cvc,
            "expiration_date"=> $this->prepaidCardStock->expiration_date,
            "is_printed"=> $this->is_printed,
        ];
    }
}
