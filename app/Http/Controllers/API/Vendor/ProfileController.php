<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\ChangePasswordRequest;
use App\Http\Requests\Vendor\ProfileUpdateRequest;
use App\Http\Resources\Vendor\ProfileResource;
use App\Models\Vendor;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="VendorProfile",
 *     description="API Endpoints of Vendor Profile"
 * )
 */
class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/vendors",
     *  summary="Profile",
     *  description="Vendor Profile",
     *  operationId="VendorProfile",
     *  tags={"VendorProfile"},
     *  security={{"bearerAuth": {}}},
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="id", type="integer", example=""),
     *      @OA\Property(property="name", type="string", example=""),
     *      @OA\Property(property="address", type="string", example=""),
     *      @OA\Property(property="phone", type="string", example=""),
     *      @OA\Property(property="email", type="string", example=""),
     *      @OA\Property(property="image", type="string", example=""),
     *      @OA\Property(property="credit", type="double", example=""),
     *      @OA\Property(property="dept", type="double", example=""),
     *      @OA\Property(property="is_blocked", type="boolean", example=""),
     *      @OA\Property(property="is_email_verified", type="boolean", example=""),
     *      @OA\Property(property="is_phone_verified", type="boolean", example=""),
     *    ),
     *  )
     * )
     */
    public function show(Request $request)
    {
        return new ProfileResource(
            Vendor::where('id', $request->vendor_id)
                ->first()
        );
    }

    /**
     * @OA\Put(
     *  path="/api/vendors",
     *  summary="Vendor Update profile",
     *  description="Vendor Update profile",
     *  operationId="VendorUpdateProfile",
     *  tags={"VendorProfile"},
     *  security={{"bearerAuth": {}}},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(property="currency_id", type="integer", example=""),
     *       ),
     *     ),
     *  ),
     *  @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent()
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
    public function update(ProfileUpdateRequest $request)
    {
        if ($request->validated()) {
            $Vendor = Vendor::where('id', $request->vendor_id)->first();
            if ($Vendor) {
                $Vendor->update($request->validated());
                return response()->json(["data"=>[]], 200);
            }
            return response()->json([
                "message" => "Wrong Vendor ID",
                "errors" => [
                    "id" => [
                        "Wrong Vendor ID",
                    ]
                ]
            ], 422);
        }
        return response()->json([
            "message" => "No Data Sent",
            "errors" => [
                "id" => [
                    "No Data Sent",
                ]
            ]
        ], 422);
    }

    /**
     * @OA\Delete(
     *  path="/api/vendors",
     *  summary="Vendor Delete Profile",
     *  description="Vendor Delete Profile",
     *  operationId="VendorDeleteProfile",
     *  tags={"VendorProfile"},
     *  security={{"bearerAuth": {}}},
     *  @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent()
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
    public function destroy(Request $request)
    {
        $Vendor = Vendor::where('id', $request->vendor_id)->first();
        if ($Vendor) {
            $Vendor->delete();
            return response([], 200);
        }
        return response()->json([
            "message" => "Wrong ID",
            "errors" => [
                "id" => [
                    "Wrong ID",
                ]
            ]
        ], 422);
    }

    /**
     * @OA\Put(
     *  path="/api/vendors/changePassword",
     *  summary="Vendor Change Password",
     *  description="Change Password Vendor",
     *  operationId="VendorPasswordChange",
     *  tags={"VendorProfile"},
     *  security={{"bearerAuth": {}}},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"old_password","new_password","new_password_confirmation","remove_all_users_tokens"},
     *         @OA\Property(property="old_password", type="string", example=""),
     *         @OA\Property(property="new_password", type="string", example=""),
     *         @OA\Property(property="new_password_confirmation", type="string", example=""),
     *         @OA\Property(property="remove_all_users_tokens", type="string", example=""),
     *       ),
     *     ),
     *  ),
     *  @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent()
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
    public function changePassword(ChangePasswordRequest $request)
    {
        $Vendor = Vendor::where('id', $request->vendor_id)->first();
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
        if (Hash::check($request->old_password, $Vendor->password)) {
            $Vendor->update([
                'password' => $request->new_password,
            ]);
        }

        if ($request->remove_all_users_tokens == "true") {
            $VendorTokens = PersonalAccessToken::where("tokenable_type", 'App\Models\Vendor')
            ->where('token', '!=', str_replace('Bearer ', '', $request->header('Authorization')))
            ->first();
            foreach ($VendorTokens as $key => $VendorToken) {
                $VendorToken->delete();
            }
        }
        return response()->json(["data"=>[]], 200);
    }
}
