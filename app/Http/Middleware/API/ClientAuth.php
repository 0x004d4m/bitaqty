<?php

namespace App\Http\Middleware\API;

use App\Models\Client;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->hasHeader('Authorization')){
            $Client = Client::select('*')
                ->where('access_token', str_replace('Bearer ', '', $request->header('Authorization')))
                ->first();
            if ($Client) {
                $request->merge(['client_id' => $Client->id]);
                return $next($request);
            } else {
                return response()->json([], 401);
            }
        }
        return response()->json([], 401);
    }
}
