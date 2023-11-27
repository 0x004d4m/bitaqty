<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Auth\ForgetPasswordRequest;
use App\Http\Requests\General\Auth\LoginRequest;
use App\Http\Requests\General\Auth\OtpRequest;
use App\Http\Requests\General\Auth\RefreshTokenRequest;
use App\Http\Requests\Client\Auth\RegisterRequest;
use App\Http\Requests\General\Auth\ResetPasswordRequest;
use App\Mail\Client\RegisterMail;
use App\Models\Client;
use App\Models\PersonalAccessToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * @OA\Tag(
 *     name="ClientAuth",
 *     description="API Endpoints of Client Auth"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *  path="/api/clients/auth/register",
     *  summary="Register",
     *  description="Register Client",
     *  operationId="ClientAuthRegister",
     *  tags={"ClientAuth"},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"name","email","phone","password","password_confirmation","address","commercial_name","fcm_token","country_id","state_id","currency_id"},
     *         @OA\Property(property="name", type="string", example=""),
     *         @OA\Property(property="email", type="string", example=""),
     *         @OA\Property(property="phone", type="string", example=""),
     *         @OA\Property(property="password", type="string", example=""),
     *         @OA\Property(property="password_confirmation", type="string", example=""),
     *         @OA\Property(property="address", type="string", example=""),
     *         @OA\Property(property="commercial_name", type="string", example=""),
     *         @OA\Property(property="fcm_token", type="string", example=""),
     *         @OA\Property(property="country_id", type="integer", example=""),
     *         @OA\Property(property="state_id", type="integer", example=""),
     *         @OA\Property(property="currency_id", type="integer", example=""),
     *       ),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="otp_token", type="string", example="")
     *    )
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *      @OA\Property(property="message", type="string", example=""),
     *      @OA\Property(property="errors", type="object",
     *         @OA\Property(property="dynamic-error-keys", type="array",
     *           @OA\Items(type="string")
     *         )
     *       )
     *     )
     *  )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $Client = Client::create($request->input());
        if ($Client) {
            $plainTextToken = $Client->createToken('ClientOtpToken')->plainTextToken;
            $plainTextToken = explode('|', $plainTextToken)[0];
            $ClientOtpToken = PersonalAccessToken::where('id', $plainTextToken)->first();
            $ClientOtpToken->update([
                "code" => 1234
            ]);
            if (PersonalAccessToken::where("token", $request->fcm_token)->count() == 0) {
                $ClientFcmToken = PersonalAccessToken::create([
                    "tokenable_type" => 'App\Models\Client',
                    "tokenable_id" => $Client->id,
                    "name" => 'ClientFcmToken',
                    "token" => $request->fcm_token,
                    "abilities" => '["*"]',
                ]);
            }
            if(env('APP_ENV')=='production'){
                $plainTextToken = $Client->createToken('ClientVerifyEmailToken')->plainTextToken;
                $plainTextToken = explode('|', $plainTextToken)[0];
                $ClientVerifyEmailToken = PersonalAccessToken::where('id', $plainTextToken)->first();
                Mail::to($Client->email)->send(new RegisterMail($ClientVerifyEmailToken->token, $Client->id));
            }
            return response()->json([
                "data" => [
                    "otp_token" => $ClientOtpToken->token,
                ]
            ], 200);
        } else {
            return response()->json([
                "message" => "Error Creating Client",
                "errors" => [
                    "email" => [
                        "Error Creating Client",
                    ]
                ]
            ], 422);
        }
    }

    /**
     * @OA\Post(
     *  path="/api/clients/auth/login",
     *  summary="Login",
     *  description="Login Client",
     *  operationId="ClientAuthLogin",
     *  tags={"ClientAuth"},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"username","password","fcm_token"},
     *         @OA\Property(property="username", type="string", example=""),
     *         @OA\Property(property="password", type="string", example=""),
     *         @OA\Property(property="fcm_token", type="string", example=""),
     *       ),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="otp_token", type="string", example="")
     *    )
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *      @OA\Property(property="message", type="string", example=""),
     *      @OA\Property(property="errors", type="object",
     *         @OA\Property(property="dynamic-error-keys", type="array",
     *           @OA\Items(type="string")
     *         )
     *       )
     *     )
     *  )
     * )
     */
    public function login(LoginRequest $request)
    {
        $Client = Client::where('phone', $request->username)->first();
        if (!$Client) {
            $Client = Client::where('email', $request->username)->first();
            if (!$Client) {
                return response()->json([
                    "message" => "Wrong Username",
                    "errors" => [
                        "username" => [
                            "Wrong Username",
                        ]
                    ]
                ], 422);
            }
        }
        if (Hash::check($request->password, $Client->password)) {
            $plainTextToken = $Client->createToken('ClientOtpToken')->plainTextToken;
            $plainTextToken = explode('|', $plainTextToken)[0];
            $ClientOtpToken = PersonalAccessToken::where('id', $plainTextToken)->first();
            $ClientOtpToken->update([
                "code" => 1234
            ]);
            if (PersonalAccessToken::where("token", $request->fcm_token)->count() == 0) {
                $ClientFcmToken = PersonalAccessToken::create([
                    "tokenable_type" => 'App\Models\Client',
                    "tokenable_id" => $Client->id,
                    "name" => 'ClientFcmToken',
                    "token" => $request->fcm_token,
                    "abilities" => '["*"]',
                ]);
            }
            // if(env('APP_ENV')=='production'){
            //     Mail::to($Client->email)->send(new RegisterMail());
            // }
            return response()->json([
                "data" => [
                    "otp_token" => $ClientOtpToken->token,
                ]
            ], 200);
        }
        return response()->json([
            "message" => "Wrong Password",
            "errors" => [
                "password" => [
                    "Wrong Password",
                ]
            ]
        ], 422);
    }

    /**
     * @OA\Post(
     *  path="/api/clients/auth/otp",
     *  summary="otp",
     *  description="OTP Client",
     *  operationId="ClientAuthOTP",
     *  tags={"ClientAuth"},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"otp_token","code"},
     *         @OA\Property(property="otp_token", type="string", example=""),
     *         @OA\Property(property="code", type="string", example=""),
     *       ),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="access_token", type="string", example=""),
     *      @OA\Property(property="refresh_token", type="string", example=""),
     *      @OA\Property(property="access_token_expiry", type="string", example=""),
     *    )
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *      @OA\Property(property="message", type="string", example=""),
     *      @OA\Property(property="errors", type="object",
     *         @OA\Property(property="dynamic-error-keys", type="array",
     *           @OA\Items(type="string")
     *         )
     *       )
     *     )
     *  )
     * )
     */
    public function otp(OtpRequest $request)
    {
        $ClientOtpToken = PersonalAccessToken::where('name', 'ClientOtpToken')
            ->where("tokenable_type", 'App\Models\Client')
            ->where('token', $request->otp_token)
            ->where('code', $request->code)
            ->first();
        if ($ClientOtpToken) {
            $Client = Client::where('id', $ClientOtpToken->tokenable_id)->first();
            if ($Client) {
                $access_token_expiry = Carbon::now()->addDays(10);
                $ClientAccessToken = $Client->createToken('ClientAccessToken', ["*"], $access_token_expiry)->plainTextToken;
                $ClientAccessToken = explode('|', $ClientAccessToken)[0];
                $ClientAccessToken = PersonalAccessToken::where('id', $ClientAccessToken)->first();
                $ClientRefreshToken = $Client->createToken('ClientRefreshToken')->plainTextToken;
                $ClientRefreshToken = explode('|', $ClientRefreshToken)[0];
                $ClientRefreshToken = PersonalAccessToken::where('id', $ClientRefreshToken)->first();
                $ClientOtpToken->delete();
                $Client->update([
                    "is_phone_verified" => true,
                ]);
                return response()->json([
                    "data" => [
                        "access_token" => $ClientAccessToken->token,
                        "refresh_token" => $ClientRefreshToken->token,
                        "access_token_expiry" => $access_token_expiry,
                    ]
                ], 200);
            } else {
                return response()->json([
                    "message" => "Wrong Code",
                    "errors" => [
                        "otp" => [
                            "Wrong Code",
                        ]
                    ]
                ], 422);
            }
        } else {
            return response()->json([
                "message" => "Wrong Code",
                "errors" => [
                    "otp" => [
                        "Wrong Code",
                    ]
                ]
            ], 422);
        }
    }

    /**
     * @OA\Post(
     *  path="/api/clients/auth/forget",
     *  summary="Forget Password",
     *  description="Forget Password Client",
     *  operationId="ClientAuthForget",
     *  tags={"ClientAuth"},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"username"},
     *         @OA\Property(property="username", type="string", example=""),
     *       ),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="forget_token", type="string", example="")
     *    )
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *      @OA\Property(property="message", type="string", example=""),
     *      @OA\Property(property="errors", type="object",
     *         @OA\Property(property="dynamic-error-keys", type="array",
     *           @OA\Items(type="string")
     *         )
     *       )
     *     )
     *  )
     * )
     */
    public function forget(ForgetPasswordRequest $request)
    {
        $Client = Client::where('phone', $request->username)->first();
        if (!$Client) {
            $Client = Client::where('email', $request->username)->first();
            if (!$Client) {
                return response()->json([
                    "message" => "Wrong Username",
                    "errors" => [
                        "username" => [
                            "Wrong Username",
                        ]
                    ]
                ], 422);
            }
        }
        $ClientForgetToken = $Client->createToken('ClientForgetToken')->plainTextToken;
        $ClientForgetToken = explode('|', $ClientForgetToken)[0];
        $ClientForgetToken = PersonalAccessToken::where('id', $ClientForgetToken)->first();
        // if(env('APP_ENV')=='production'){
        //     Mail::to($Client->email)->send(new RegisterMail());
        // }
        return response()->json([
            "data" => [
                "token" => $ClientForgetToken->token,
            ]
        ], 200);
    }

    /**
     * @OA\Post(
     *  path="/api/clients/auth/reset",
     *  summary="Reset Password",
     *  description="Reset Password Client",
     *  operationId="ClientAuthReset",
     *  tags={"ClientAuth"},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"forget_token","password","password_confirmed"},
     *         @OA\Property(property="forget_token", type="string", example=""),
     *         @OA\Property(property="password", type="string", example=""),
     *         @OA\Property(property="password_confirmed", type="string", example=""),
     *       ),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *      @OA\Property(property="message", type="string", example=""),
     *      @OA\Property(property="errors", type="object",
     *         @OA\Property(property="dynamic-error-keys", type="array",
     *           @OA\Items(type="string")
     *         )
     *       )
     *     )
     *  )
     * )
     */
    public function reset(ResetPasswordRequest $request)
    {
        $ClientForgetToken = PersonalAccessToken::where('name', 'ClientForgetToken')
            ->where("tokenable_type", 'App\Models\Client')
            ->where('token', $request->forget_token)
            ->first();
        if ($ClientForgetToken) {
            $Client = Client::where('id', $ClientForgetToken->tokenable_id)->first();
            if (!$Client) {
                return response()->json([
                    "message" => "Wrong Token",
                    "errors" => [
                        "forget_token" => [
                            "Wrong Token",
                        ]
                    ]
                ], 422);
            }
            $Client->update([
                'password' => $request->password,
            ]);
            return response()->json(["data" => []], 200);
        } else {
            return response()->json([
                "message" => "Wrong Token",
                "errors" => [
                    "forget_token" => [
                        "Wrong Token",
                    ]
                ]
            ], 422);
        }
    }

    /**
     * @OA\Post(
     *  path="/api/clients/auth/refresh",
     *  summary="refresh",
     *  description="Client Refresh Token",
     *  operationId="ClientRefreshToken",
     *  tags={"ClientAuth"},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"refresh_token"},
     *         @OA\Property(property="refresh_token", type="string", example=""),
     *       ),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="access_token", type="string", example=""),
     *      @OA\Property(property="refresh_token", type="string", example=""),
     *      @OA\Property(property="access_token_expiry", type="string", example=""),
     *    )
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *      @OA\Property(property="message", type="string", example=""),
     *      @OA\Property(property="errors", type="object",
     *         @OA\Property(property="dynamic-error-keys", type="array",
     *           @OA\Items(type="string")
     *         )
     *       )
     *     )
     *  )
     * )
     */
    public function refresh(RefreshTokenRequest $request)
    {
        $ClientRefreshToken = PersonalAccessToken::where('name', 'ClientRefreshToken')
        ->where("tokenable_type", 'App\Models\Client')
        ->where('token', $request->refresh_token)
        ->first();
        if ($ClientRefreshToken) {
            $Client = Client::where('id', $ClientRefreshToken->tokenable_id)->first();
            if ($Client) {
                $access_token_expiry = Carbon::now()->addDays(10);
                $ClientAccessToken = $Client->createToken('ClientAccessToken', ["*"], $access_token_expiry)->plainTextToken;
                $ClientAccessToken = explode('|', $ClientAccessToken)[0];
                $ClientAccessToken = PersonalAccessToken::where('id', $ClientAccessToken)->first();
                $ClientRefreshToken = $Client->createToken('ClientRefreshToken')->plainTextToken;
                $ClientRefreshToken = explode('|', $ClientRefreshToken)[0];
                $ClientRefreshToken = PersonalAccessToken::where('id', $ClientRefreshToken)->first();
                $ClientRefreshToken->delete();
                return response()->json([
                    "data" => [
                        "access_token" => $ClientAccessToken->token,
                        "refresh_token" => $ClientRefreshToken->token,
                        "access_token_expiry" => $access_token_expiry,
                    ]
                ], 200);
            } else {
                return response()->json([
                    "message" => "Wrong Token",
                    "errors" => [
                        "refresh_token" => [
                            "Wrong Token",
                        ]
                    ]
                ], 422);
            }
        } else {
            return response()->json([
                "message" => "Wrong Token",
                "errors" => [
                    "refresh_token" => [
                        "Wrong Token",
                    ]
                ]
            ], 422);
        }
    }
}
