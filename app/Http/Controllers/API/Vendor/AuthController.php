<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Auth\ForgetPasswordRequest;
use App\Http\Requests\Vendor\Auth\LoginRequest;
use App\Http\Requests\Vendor\Auth\OtpRequest;
use App\Http\Requests\Vendor\Auth\RegisterRequest;
use App\Http\Requests\Vendor\Auth\ResetPasswordRequest;
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
     *  path="/api/vendors/auth/register",
     *  summary="Register",
     *  description="Register Vendor",
     *  operationId="VendorAuthRegister",
     *  tags={"VendorAuth"},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"name","email","phone","password","password_confirmation","address","fcm_token","country_id","state_id","currency_id"},
     *         @OA\Property(property="name", type="string", example=""),
     *         @OA\Property(property="email", type="string", example=""),
     *         @OA\Property(property="phone", type="string", example=""),
     *         @OA\Property(property="password", type="string", example=""),
     *         @OA\Property(property="password_confirmation", type="string", example=""),
     *         @OA\Property(property="address", type="string", example=""),
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
        $Vendor = Vendor::create($request->input());
        if ($Vendor) {
            $Vendor->update([
                'otp_token' => $Vendor->createToken('VendorOtpToken')->plainTextToken,
                'otp_code' => 1234,
            ]);
            // if(env('APP_ENV')=='production'){
            //     Mail::to($Vendor->email)->send(new RegisterMail());
            // }
            return response()->json([
                "otp_token" => $Vendor->otp_token,
            ], 200);
        } else {
            return response()->json([
                "message" => "Error Creating Vendor",
                "errors" => [
                    "email" => [
                        "Error Creating Vendor",
                    ]
                ]
            ], 422);
        }
    }

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
     *         @OA\Property(property="username", type="string", example="email or password"),
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
            $Vendor->update([
                'fcm_token' => $request->fcm_token,
                'otp_token' => $Vendor->createToken('VendorOtpToken')->plainTextToken,
                'otp_code' => 1234,
            ]);
            // if(env('APP_ENV')=='production'){
            //     Mail::to($Vendor->email)->send(new RegisterMail());
            // }
            return response()->json([
                "otp_token" => $Vendor->otp_token,
            ], 200);
        }
        return response()->json([
            "message" => "Wrong Password",
            "errors" => [
                "username" => [
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
        $Vendor = Vendor::where('otp_token', $request->otp)->where('otp_code', $request->code)->first();
        if ($Vendor) {
            $access_token_expiry = Carbon::now()->addDays(10);
            $Vendor->update([
                'otp_token' => null,
                'otp_code' => null,
                "is_phone_verified" => true,
                "access_token" => $Vendor->createToken('VendorAccessToken', ["*"], $access_token_expiry)->plainTextToken,
                "refresh_token" => $Vendor->createToken('VendorRefreshToken')->plainTextToken,
            ]);
            return response()->json([
                "access_token" => $Vendor->access_token,
                "refresh_token" => $Vendor->refresh_token,
                "access_token_expiry" => $access_token_expiry,
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
     *         @OA\Property(property="username", type="string", example="email or password"),
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
        $Vendor->update([
            'forget_token' => $Vendor->createToken('VendorForgetToken')->plainTextToken,
        ]);
        // if(env('APP_ENV')=='production'){
        //     Mail::to($Vendor->email)->send(new RegisterMail());
        // }
        return response()->json([
            "token" => $Vendor->forget_token,
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
     *    @OA\JsonContent(
     *      @OA\Property(property="message", type="string", example="")
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
    public function reset(ResetPasswordRequest $request)
    {
        $Vendor = Vendor::where('forget_token', $request->token)->first();
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
            'otp_token' => null,
            'otp_code' => null,
            'access_token' => null,
            'refresh_token' => null,
            'forget_token' => null,
        ]);
        return response()->json([
            "message" => "Password Resetted Successfully",
        ], 200);
    }
}
