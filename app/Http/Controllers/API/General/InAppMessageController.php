<?php

namespace App\Http\Controllers\API\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\InAppMessageResource;
use App\Models\InAppMessage;
use Illuminate\Http\Request;

class InAppMessageController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/inAppMessages",
     *  summary="In App Messages",
     *  description="In App Messages",
     *  operationId="InAppMessages",
     *  tags={"General"},
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
     *              @OA\Property(property="action", type="string", example=""),
     *              @OA\Property(property="is_important", type="string", example=""),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        return InAppMessageResource::collection(
            InAppMessage::where('type', $request->filter['type'])->where('is_active', 1)->limit(1)->get()
        );
    }
}
