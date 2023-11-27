<?php

namespace App\Http\Resources\Vendor;

use App\Http\Resources\Client\CountryResource;
use App\Http\Resources\General\CurrencyResource;
use App\Http\Resources\General\GroupResource;
use App\Http\Resources\General\StateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'credit' => $this->credit,
            'dept' => $this->dept,
            'is_blocked' => $this->is_blocked,
            'is_email_verified' => $this->is_email_verified,
            'is_phone_verified' => $this->is_phone_verified,
            'country' => new CountryResource($this->country),
            'state' => new StateResource($this->state),
            'currency' => new CurrencyResource($this->currency),
        ];
    }
}
