<?php

namespace App\Http\Resources\Client;

use App\Http\Resources\General\CreditStatusResource;
use App\Http\Resources\General\CreditTypeResource;
use App\Http\Resources\General\SupportedAccountResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "image" => $this->image,
            "amount" => $this->amount,
            "notes" => $this->notes,
            "deposit_or_withdraw" => $this->deposit_or_withdraw,
            "credit_status" =>  new CreditStatusResource($this->creditStatus),
            "credit_type" =>  new CreditTypeResource($this->creditType),
            "supported_account" =>  new SupportedAccountResource($this->supportedAccount),
            "from" =>  new CreditFromResource($this->userableFrom),
        ];
    }
}