<?php

namespace App\Http\Resources\Client;

use App\Http\Resources\Client\CountryResource;
use App\Http\Resources\General\CurrencyResource;
use App\Http\Resources\General\StateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'image' => $this->image,
            'country' => new CountryResource($this->country),
            'state' => new StateResource($this->state),
            'currency' => new CurrencyResource($this->currency),
        ];
    }
}
