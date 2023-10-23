<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Mail\Client\RegisterMail;
use App\Models\Client;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * @OA\Tag(
 *     name="ClientVerifyEmail",
 *     description="API Endpoints of Clients Verify Email"
 * )
 */
class VerifyEmail extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/clients/verifyEmail",
     *  summary="Issues",
     *  description="Client Send Verify Email",
     *  operationId="SendVerifyEmail",
     *  tags={"VerifyEmail"},
     *  security={{"bearerAuth": {}}},
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *  ),
     * )
     */
    public function send(Request $request)
    {
        $Client = Client::where('id', $request->client_id)->first();
        $plainTextToken = $Client->createToken('ClientVerifyEmailToken')->plainTextToken;
        $plainTextToken = explode('|', $plainTextToken)[0];
        $ClientVerifyEmailToken = PersonalAccessToken::where('id', $plainTextToken)->first();
        if (env('APP_ENV') == 'production') {
            Mail::to($Client->email)->send(new RegisterMail($ClientVerifyEmailToken->token, $Client->id));
        }
        return response()->json(["data" => []], 200);
    }

    public function check(Request $request)
    {
        $Client = Client::where('id', $request->client_id)->first();
        $ClientVerifyEmailToken = PersonalAccessToken::where('id', $request->token)->first();
        if ($ClientVerifyEmailToken) {
            $ClientVerifyEmailToken->delete();
            $Client->update([
                'is_email_verified' => 1
            ]);
            return response()->json(null, 200);
        }
        return response()->json(null, 404);
    }
}
