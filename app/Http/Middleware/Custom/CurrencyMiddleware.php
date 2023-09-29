<?php

namespace App\Http\Middleware\Custom;

use App\Models\Currency;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CurrencyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (str_contains(url()->current(), '/admin')) {
            if (!Session::has('currency')) {
                Session::put('currency', Currency::where('id', 1)->first()->id);
            }
        }else {
            Session::put('currency', Currency::where('id', 1)->first()->id);
        }
        return $next($request);
    }
}
