<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="ClientNotification",
 *     description="API Endpoints of Client Notification"
 * )
 */
class NotificationController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/clients/notifications",
     *  summary="Notifications",
     *  description="Client Notifications",
     *  operationId="ClientNotifications",
     *  tags={"ClientNotification"},
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
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        return NotificationResource::collection(
            Notification::where('userable_type', 'App\Models\Client')
                ->where('userable_id', $request->client_id)
                ->paginate()
        );
    }

    /**
     * @OA\Delete(
     *  path="/api/clients/notifications/{id}",
     *  summary="Notifications",
     *  description="Client Delete Notifications",
     *  operationId="ClientDeleteNotifications",
     *  tags={"ClientNotification"},
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
        $Notification = Notification::where('id', $id)->first();
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
}
