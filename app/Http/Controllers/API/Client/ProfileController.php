<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ChangePasswordRequest;
use App\Http\Requests\Client\ProfileUpdateRequest;
use App\Http\Resources\Client\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="ClientProfile",
 *     description="API Endpoints of Client Profile"
 * )
 */
class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/clients",
     *  summary="Profile",
     *  description="Client Profile",
     *  operationId="ClientProfile",
     *  tags={"ClientProfile"},
     *  security={{"bearerAuth": {}}},
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="id", type="integer", example=""),
     *      @OA\Property(property="title", type="string", example=""),
     *      @OA\Property(property="description", type="string", example=""),
     *      @OA\Property(property="image", type="string", example=""),
     *      @OA\Property(property="data", type="object", example={}),
     *      @OA\Property(property="is_read", type="string", example=""),
     *    ),
     *  )
     * )
     */
    public function show(Request $request)
    {
        return new ClientResource(
            Client::where('id', $request->client_id)
                ->first()
        );
    }

    /**
     * @OA\Put(
     *  path="/api/clients",
     *  summary="Update profile",
     *  description="Update profile",
     *  operationId="UpdateProfile",
     *  tags={"ClientProfile"},
     *  security={{"bearerAuth": {}}},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(property="name", type="string", example=""),
     *         @OA\Property(property="address", type="string", example=""),
     *         @OA\Property(property="phone", type="string", example=""),
     *         @OA\Property(property="commercial_name", type="string", example=""),
     *         @OA\Property(property="email", type="string", example=""),
     *         @OA\Property(property="country_id", type="integer", example=""),
     *         @OA\Property(property="state_id", type="integer", example=""),
     *         @OA\Property(property="currency_id", type="integer", example=""),
     *         @OA\Property(property="image", type="file", format="file"),
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
            $Client = Client::where('id', $request->client_id)->first();
            if ($Client) {
                $Client->update($request->validated());
                return response()->json(["data"=>[]], 200);
            }
            return response()->json([
                "message" => "Wrong Client ID",
                "errors" => [
                    "id" => [
                        "Wrong Client ID",
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
     *  path="/api/clients",
     *  summary="Client Delete Profile",
     *  description="Client Delete Profile",
     *  operationId="ClientDeleteProfile",
     *  tags={"ClientProfile"},
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
        $Client = Client::where('id', $request->client_id)->first();
        if ($Client) {
            $Client->delete();
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
     *  path="/api/clients/changePassword",
     *  summary="Change Password",
     *  description="Change Password Client",
     *  operationId="ClientPasswordChange",
     *  tags={"ClientProfile"},
     *  security={{"bearerAuth": {}}},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"old_pasword","new_password","new_password_confirmed"},
     *         @OA\Property(property="old_pasword", type="string", example=""),
     *         @OA\Property(property="new_password", type="string", example=""),
     *         @OA\Property(property="password_confirmed", type="string", example=""),
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
        $Client = Client::where('id', $request->client_id)->first();
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
        if (Hash::check($request->old_pasword, $Client->password)) {
            $Client->update([
                'password' => $request->new_password,
            ]);
        }
        return response()->json(["data"=>[]], 200);
    }
}
