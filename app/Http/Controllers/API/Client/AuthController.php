<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Auth\ForgetPasswordRequest;
use App\Http\Requests\Client\Auth\LoginRequest;
use App\Http\Requests\Client\Auth\OtpRequest;
use App\Http\Requests\Client\Auth\RegisterRequest;
use App\Http\Requests\Client\Auth\ResetPasswordRequest;
use App\Mail\Client\RegisterMail;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
        if($Client){
            $Client->update([
                'otp_token' => $Client->createToken('ClientOtpToken')->plainTextToken,
                'otp_code' => 1234,
            ]);
            // if(env('APP_ENV')=='production'){
            //     Mail::to($Client->email)->send(new RegisterMail());
            // }
            return response()->json([
                "otp_token" => $Client->otp_token,
            ], 200);
        }else{
            return response()->json([
                "message" => "Error Creating Client",
                "errors" => [
                    "email"=>[
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
        $Client = Client::where('phone',$request->username)->first();
        if(!$Client){
            $Client = Client::where('email', $request->username)->first();
            if(!$Client){
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
        if (Hash::check($request->password,$Client->password)) {
            $Client->update([
                'fcm_token' => $request->fcm_token,
                'otp_token' => $Client->createToken('ClientOtpToken')->plainTextToken,
                'otp_code' => 1234,
            ]);
            // if(env('APP_ENV')=='production'){
            //     Mail::to($Client->email)->send(new RegisterMail());
            // }
            return response()->json([
                "otp_token" => $Client->otp_token,
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
     *          required={"otp","code"},
     *         @OA\Property(property="otp", type="string", example=""),
     *         @OA\Property(property="code", type="string", example=""),
     *         @OA\Property(property="access_token_expiry", type="string", example=""),
     *       ),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="access_token", type="string", example=""),
     *      @OA\Property(property="refresh_token", type="string", example=""),
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
        $Client = Client::where('otp_token', $request->otp)->where('otp_code', $request->code)->first();
        if ($Client) {
            $access_token_expiry = Carbon::now()->addDays(10);
            $Client->update([
                'otp_token' => null,
                'otp_code' => null,
                "is_phone_verified" => true,
                "access_token" => $Client->createToken('ClientAccessToken', ["*"], $access_token_expiry)->plainTextToken,
                "refresh_token" => $Client->createToken('ClientRefreshToken')->plainTextToken,
            ]);
            return response()->json([
                "access_token" => $Client->access_token,
                "refresh_token" => $Client->refresh_token,
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
        $Client->update([
            'forget_token' => $Client->createToken('ClientForgetToken')->plainTextToken,
        ]);
        // if(env('APP_ENV')=='production'){
        //     Mail::to($Client->email)->send(new RegisterMail());
        // }
        return response()->json([
            "token" => $Client->forget_token,
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
     *          required={"username"},
     *         @OA\Property(property="token", type="string", example=""),
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
        $Client = Client::where('forget_token', $request->token)->first();
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
