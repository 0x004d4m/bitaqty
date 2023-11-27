<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Auth\RefreshTokenRequest;
use App\Http\Requests\General\Auth\ForgetPasswordRequest;
use App\Http\Requests\General\Auth\LoginRequest;
use App\Http\Requests\General\Auth\OtpRequest;
use App\Http\Requests\General\Auth\ResetPasswordRequest;
use App\Models\PersonalAccessToken;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="VendorAuth",
 *     description="API Endpoints of Vendor Auth"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *  path="/api/vendors/auth/login",
     *  summary="Login",
     *  description="Login Vendor",
     *  operationId="VendorAuthLogin",
     *  tags={"VendorAuth"},
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
        $Vendor = Vendor::where('phone', $request->username)->first();
        if (!$Vendor) {
            $Vendor = Vendor::where('email', $request->username)->first();
            if (!$Vendor) {
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
        if (Hash::check($request->password, $Vendor->password)) {
            $plainTextToken = $Vendor->createToken('VendorOtpToken')->plainTextToken;
            $plainTextToken = explode('|', $plainTextToken)[0];
            $VendorOtpToken = PersonalAccessToken::where('id', $plainTextToken)->first();
            $VendorOtpToken->update([
                "code" => 1234
            ]);
            if (PersonalAccessToken::where("token", $request->fcm_token)->count() == 0) {
                $VendorFcmToken = PersonalAccessToken::create([
                    "tokenable_type" => 'App\Models\Vendor',
                    "tokenable_id" => $Vendor->id,
                    "name" => 'VendorFcmToken',
                    "token" => $request->fcm_token,
                    "abilities" => '["*"]',
                ]);
            }
            // if(env('APP_ENV')=='production'){
            //     Mail::to($Vendor->email)->send(new RegisterMail());
            // }
            return response()->json([
                "data" => [
                    "otp_token" => $VendorOtpToken->token,
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
     *  path="/api/vendors/auth/otp",
     *  summary="otp",
     *  description="OTP Vendor",
     *  operationId="VendorAuthOTP",
     *  tags={"VendorAuth"},
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
        $VendorOtpToken = PersonalAccessToken::where('name', 'VendorOtpToken')
        ->where("tokenable_type", 'App\Models\Vendor')
        ->where('token', $request->otp_token)
            ->where('code', $request->code)
            ->first();
        if ($VendorOtpToken) {
            $Vendor = Vendor::where('id', $VendorOtpToken->tokenable_id)->first();
            if ($Vendor) {
                $access_token_expiry = Carbon::now()->addDays(10);
                $VendorAccessToken = $Vendor->createToken('VendorAccessToken', ["*"], $access_token_expiry)->plainTextToken;
                $VendorAccessToken = explode('|', $VendorAccessToken)[0];
                $VendorAccessToken = PersonalAccessToken::where('id', $VendorAccessToken)->first();
                $VendorRefreshToken = $Vendor->createToken('VendorRefreshToken')->plainTextToken;
                $VendorRefreshToken = explode('|', $VendorRefreshToken)[0];
                $VendorRefreshToken = PersonalAccessToken::where('id', $VendorRefreshToken)->first();
                $VendorOtpToken->delete();
                $Vendor->update([
                    "is_phone_verified" => true,
                ]);
                return response()->json([
                    "data" => [
                        "access_token" => $VendorAccessToken->token,
                        "refresh_token" => $VendorRefreshToken->token,
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
     *  path="/api/vendors/auth/forget",
     *  summary="Forget Password",
     *  description="Forget Password Vendor",
     *  operationId="VendorAuthForget",
     *  tags={"VendorAuth"},
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
        $Vendor = Vendor::where('phone', $request->username)->first();
        if (!$Vendor) {
            $Vendor = Vendor::where('email', $request->username)->first();
            if (!$Vendor) {
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
        $VendorForgetToken = $Vendor->createToken('VendorForgetToken')->plainTextToken;
        $VendorForgetToken = explode('|', $VendorForgetToken)[0];
        $VendorForgetToken = PersonalAccessToken::where('id', $VendorForgetToken)->first();
        // if(env('APP_ENV')=='production'){
        //     Mail::to($Vendor->email)->send(new RegisterMail());
        // }
        return response()->json([
            "data" => [
                "token" => $VendorForgetToken->token,
            ]
        ], 200);
    }

    /**
     * @OA\Post(
     *  path="/api/vendors/auth/reset",
     *  summary="Reset Password",
     *  description="Reset Password Vendor",
     *  operationId="VendorAuthReset",
     *  tags={"VendorAuth"},
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
        $VendorForgetToken = PersonalAccessToken::where('name', 'VendorForgetToken')
        ->where("tokenable_type", 'App\Models\Vendor')
        ->where('token', $request->forget_token)
            ->first();
        if ($VendorForgetToken) {
            $Vendor = Vendor::where('id', $VendorForgetToken->tokenable_id)->first();
            if (!$Vendor) {
                return response()->json([
                    "message" => "Wrong Token",
                    "errors" => [
                        "forget_token" => [
                            "Wrong Token",
                        ]
                    ]
                ], 422);
            }
            $Vendor->update([
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
     *  path="/api/vendors/auth/refresh",
     *  summary="refresh",
     *  description="Vendor Refresh Token",
     *  operationId="VendorRefreshToken",
     *  tags={"VendorAuth"},
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
        $VendorRefreshToken = PersonalAccessToken::where('name', 'VendorRefreshToken')
        ->where("tokenable_type", 'App\Models\Vendor')
        ->where('token', $request->refresh_token)
            ->first();
        if ($VendorRefreshToken) {
            $Vendor = Vendor::where('id', $VendorRefreshToken->tokenable_id)->first();
            if ($Vendor) {
                $access_token_expiry = Carbon::now()->addDays(10);
                $VendorAccessToken = $Vendor->createToken('VendorAccessToken', ["*"], $access_token_expiry)->plainTextToken;
                $VendorAccessToken = explode('|', $VendorAccessToken)[0];
                $VendorAccessToken = PersonalAccessToken::where('id', $VendorAccessToken)->first();
                $VendorRefreshToken = $Vendor->createToken('VendorRefreshToken')->plainTextToken;
                $VendorRefreshToken = explode('|', $VendorRefreshToken)[0];
                $VendorRefreshToken = PersonalAccessToken::where('id', $VendorRefreshToken)->first();
                $VendorRefreshToken->delete();
                return response()->json([
                    "data" => [
                        "access_token" => $VendorAccessToken->token,
                        "refresh_token" => $VendorRefreshToken->token,
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
