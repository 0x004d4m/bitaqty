<?php

namespace App\Http\Middleware\API;

use App\Models\Client;
use App\Models\Currency;
use App\Models\PersonalAccessToken;
use App\Models\Vendor;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class UserAuth
{
    private function checkIfClient($AuthorizationHeader)
    {
        $ClientAccessToken = PersonalAccessToken::where('name', 'ClientAccessToken')
            ->where("tokenable_type", 'App\Models\Client')
            ->where('token', $AuthorizationHeader)
            ->where('expires_at', '<=', Carbon::now())
            ->first();
        if (!$ClientAccessToken) {
            return false;
        }
        $Client = Client::select('*')
            ->where('id', $ClientAccessToken->tokenable_id)
            ->first();
        if (!$Client) {
            return false;
        }
        $Currency = Currency::where('id', $Client->currency_id)->first();
        if (!$Currency) {
            $Currency = Currency::first();;
        }
        return ['id' => $Client->id, 'country_id' => $Client->country_id, 'state_id' => $Client->state_id, 'currency' => $Currency->id];
    }

    private function checkIfVendor($AuthorizationHeader)
    {
        $VendorAccessToken = PersonalAccessToken::where('name', 'VendorAccessToken')
            ->where("tokenable_type", 'App\Models\Vendor')
            ->where('token', $AuthorizationHeader)
            ->where('expires_at', '<=', Carbon::now())
            ->first();
        if (!$VendorAccessToken) {
            return false;
        }
        $Vendor = Vendor::select('*')
            ->where('id', $VendorAccessToken->tokenable_id)
            ->first();
        if (!$Vendor) {
            return false;
        }
        $Currency = Currency::where('id', $Vendor->currency_id)->first();
        if (!$Currency) {
            $Currency = Currency::first();;
        }
        return ['id' => $Vendor->id, 'country_id' => $Vendor->country_id, 'state_id' => $Vendor->state_id, 'currency' => $Currency->id];
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasHeader('Authorization')) {
            $AuthorizationHeader = str_replace('Bearer ', '', $request->header('Authorization'));
            $ClientData = $this->checkIfClient($AuthorizationHeader);
            if ($ClientData != false) {
                $request->merge(['client_id' => $ClientData['id']]);
                $request->merge(['country_id' => $ClientData['country_id']]);
                $request->merge(['state_id' => $ClientData['state_id']]);
                Session::put('currency', $ClientData['currency']);
                return $next($request);
            }
            $VendorData = $this->checkIfVendor($AuthorizationHeader);
            if ($VendorData != false) {
                $request->merge(['vendor_id' => $VendorData['id']]);
                $request->merge(['country_id' => $VendorData['country_id']]);
                $request->merge(['state_id' => $VendorData['state_id']]);
                Session::put('currency', $VendorData['currency']);
                return $next($request);
            }
        }
        return response()->json([], 401);
    }
}
