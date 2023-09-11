<?php

namespace App\Http\Resources\Client;

use App\Http\Resources\Client\CountryResource;
use App\Http\Resources\General\CurrencyResource;
use App\Http\Resources\General\GroupResource;
use App\Http\Resources\General\StateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'password' => $this->password,
            'commercial_name' => $this->commercial_name,
            'email' => $this->email,
            'image' => $this->image,
            'credit' => $this->credit,
            'is_approved' => $this->is_approved,
            'is_blocked' => $this->is_blocked,
            'can_give_credit' => $this->can_give_credit,
            'is_email_verified' => $this->is_email_verified,
            'is_phone_verified' => $this->is_phone_verified,
            'vendor' => new VendorResource($this->vendor),
            'country' => new CountryResource($this->country),
            'state' => new StateResource($this->state),
            'currency' => new CurrencyResource($this->currency),
            'group' => new GroupResource($this->group),
        ];
    }
}
