<?php

namespace App\Http\Middleware\API;

use App\Models\Client;
use App\Models\Currency;
use App\Models\Vendor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasHeader('Authorization')) {
            $Client = Client::select('*')
                ->where('access_token', str_replace('Bearer ', '', $request->header('Authorization')))
                ->first();
            if ($Client) {
                $request->merge(['client_id' => $Client->id]);
                $request->merge(['country_id' => $Client->country_id]);
                $request->merge(['state_id' => $Client->state_id]);
                $Currency = Currency::where('id', $Client->currency_id)->first();
                Session::put('currency', $Currency->id);
                return $next($request);
            } else {
                $Vendor = Vendor::select('*')
                    ->where('access_token', str_replace('Bearer ', '', $request->header('Authorization')))
                    ->first();
                if ($Vendor) {
                    $request->merge(['vendor_id' => $Vendor->id]);
                    $request->merge(['country_id' => $Vendor->country_id]);
                    $request->merge(['state_id' => $Vendor->state_id]);
                    Session::put('currency', $Vendor->id);
                    return $next($request);
                } else {
                    return response()->json([], 401);
                }
            }
        }
        return response()->json([], 401);
    }
}
