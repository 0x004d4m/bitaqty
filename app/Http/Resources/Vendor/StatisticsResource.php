<?php

namespace App\Http\Resources\Vendor;

use App\Models\Client;
use App\Models\Credit;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatisticsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $clientIds = Client::where('vendor_id', $this->id)->pluck('id');
        return [
            'clients' => Client::where('vendor_id', $this->id)->count(),
            'clients_orders' => Order::where('userable_type', Client::class)->whereIn('userable_id', $clientIds)->count(),
            'clients_credit_pending_requests' => Credit::where("userable_type", Client::class)->whereIn('userable_id', $clientIds)->where('credit_status_id', 1)->where('credit_type_id', 1)->count(),
            'profit' => 0,
        ];
    }
}
