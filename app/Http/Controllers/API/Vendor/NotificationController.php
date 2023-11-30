<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\NotificationResource;
use App\Models\UserNotification;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="VendorNotification",
 *     description="API Endpoints of Vendor Notification"
 * )
 */
class NotificationController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/vendors/notifications",
     *  summary="Notifications",
     *  description="Vendor Notifications",
     *  operationId="VendorNotifications",
     *  tags={"VendorNotification"},
     *  security={{"bearerAuth": {}}},
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(
     *          property="data",
     *          type="array",
     *          @OA\Items(
     *              @OA\Property(property="id", type="integer", example=""),
     *              @OA\Property(property="title", type="string", example=""),
     *              @OA\Property(property="description", type="string", example=""),
     *              @OA\Property(property="image", type="string", example=""),
     *              @OA\Property(property="data", type="object", example={}),
     *              @OA\Property(property="is_read", type="string", example=""),
     *              @OA\Property(property="created_at", type="string", example=""),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        return NotificationResource::collection(
            UserNotification::where('userable_type', Vendor::class)
                ->where('userable_id', $request->vendor_id)
                ->paginate()
        );
    }

    /**
     * @OA\Delete(
     *  path="/api/vendors/notifications/{id}",
     *  summary="Notifications",
     *  description="Vendor Delete Notifications",
     *  operationId="VendorDeleteNotifications",
     *  tags={"VendorNotification"},
     *  security={{"bearerAuth": {}}},
     *  @OA\Parameter(
     *     name="id",
     *     description="notification id",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *         type="integer"
     *     )
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
    public function destroy(Request $request, $id)
    {
        $Notification = UserNotification::where('id', $id)->first();
        if($Notification){
            $Notification->delete();
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
     * @OA\patch(
     *  path="/api/vendors/notifications/{id}",
     *  summary="Notifications",
     *  description="Vendor Read Notifications",
     *  operationId="VendorReadNotifications",
     *  tags={"VendorNotification"},
     *  security={{"bearerAuth": {}}},
     *  @OA\Parameter(
     *     name="id",
     *     description="notification id",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *         type="integer"
     *     )
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
    public function read(Request $request, $id)
    {
        $Notification = UserNotification::where('id', $id)->first();
        if($Notification){
            $Notification->update([
                'is_read'=>1
            ]);
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
}
