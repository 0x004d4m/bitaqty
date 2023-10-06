<?php

namespace App\Http\Middleware\API;

use App\Models\Client;
use App\Models\Currency;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CurrencyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::debug('hi'.json_encode($request));
        if($request->has('client_id')){
            $Currency = Currency::where('id', Client::where('id', $request->client_id)->first()->currency_id)->first();
        }else {
            $Currency = Currency::where('id', 1)->first();
        }
        Log::debug('hi:'. $Currency);
        Session::put('currency', $Currency->id);
        return $next($request);
    }
}
