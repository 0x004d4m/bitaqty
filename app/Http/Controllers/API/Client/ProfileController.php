<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Client\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;

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
    public function show(Request $request)
    {
        return new ClientResource(
            Client::where('id', $request->client_id)
                ->first()
        );
    }
}
