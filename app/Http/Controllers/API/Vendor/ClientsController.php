<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @OA\Tag(
 *     name="VendorClients",
 *     description="API Endpoints of Vendor Clients"
 * )
 */
class ClientsController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/vendors/clients",
     *  summary="Vendor Clients",
     *  description="Vendor Clients",
     *  operationId="VendorClients",
     *  tags={"VendorClients"},
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
     *              @OA\Property(property="name", type="string", example=""),
     *              @OA\Property(property="address", type="string", example=""),
     *              @OA\Property(property="phone", type="string", example=""),
     *              @OA\Property(property="commercial_name", type="object", example={}),
     *              @OA\Property(property="email", type="string", example=""),
     *              @OA\Property(property="image", type="string", example=""),
     *              @OA\Property(property="credit", type="double", example=""),
     *              @OA\Property(property="is_approved", type="boolean", example=""),
     *              @OA\Property(property="is_blocked", type="boolean", example=""),
     *              @OA\Property(property="can_give_credit", type="boolean", example=""),
     *              @OA\Property(property="is_email_verified", type="boolean", example=""),
     *              @OA\Property(property="is_phone_verified", type="boolean", example=""),
     *              @OA\Property(property="country", type="object", example={"id":"","name":"","code":""}),
     *              @OA\Property(property="state", type="object", example={"id":"","name":""}),
     *              @OA\Property(property="currency", type="object", example={"id":"","name":"","symbol":"","to_jod":""}),
     *              @OA\Property(property="group", type="object", example={"id":"","name":""}),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        return ClientResource::collection(
            QueryBuilder::for(Client::class)
                ->where('vendor_id', $request->vendor_id)
                ->paginate()
        );
    }
}
