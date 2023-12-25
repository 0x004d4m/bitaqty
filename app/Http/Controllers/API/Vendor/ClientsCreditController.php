<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\CreditRequestRequset;
use App\Http\Requests\Vendor\ClientAddCreditRequest;
use App\Http\Resources\General\CreditResource;
use App\Models\Client;
use App\Models\Credit;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @OA\Tag(
 *     name="VendorClientsCredits",
 *     description="API Endpoints of Vendor Clients Credits"
 * )
 */
class ClientsCreditController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/vendors/clients_credits",
     *  summary="Vendor Clients Credits",
     *  description="Vendor Clients Credits",
     *  operationId="VendorClientsCredits",
     *  tags={"VendorClientsCredits"},
     *  security={{"bearerAuth": {}}},
     *  @OA\Parameter(
     *    name="filter[category_id]",
     *    in="query",
     *    description="Filter Credits by category_id",
     *    example="1",
     *    required=false,
     *    @OA\Schema(type="int")
     *  ),
     *  @OA\Parameter(
     *    name="filter[subcategory_id]",
     *    in="query",
     *    description="Filter Credits by subcategory_id",
     *    example="1",
     *    required=false,
     *    @OA\Schema(type="int")
     *  ),
     *  @OA\Parameter(
     *    name="filter[created_at]",
     *    in="query",
     *    description="Filter Credits by created_at date range",
     *    example="2018-01-01,2018-12-31",
     *    required=false,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(
     *    name="filter[credit_status_id]",
     *    in="query",
     *    description="Filter Credits by credit_status_id",
     *    example="1",
     *    required=false,
     *    @OA\Schema(type="int")
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(
     *          property="data",
     *          type="array",
     *          @OA\Items(
     *              @OA\Property(property="id", type="integer", example=""),
     *              @OA\Property(property="image", type="string", example=""),
     *              @OA\Property(property="amount", type="string", example=""),
     *              @OA\Property(property="notes", type="string", example=""),
     *              @OA\Property(property="deposit_or_withdraw", type="string", example=""),
     *              @OA\Property(property="credit_type", type="object", example={"id":"","name":""}),
     *              @OA\Property(property="supported_account", type="object", example={"id":"","name":"","image":""}),
     *              @OA\Property(property="credit_status", type="object", example={"id":"","name":""}),
     *              @OA\Property(property="from", type="object", example={"id":"","name":"","email":"","phone":"","image":""}),
     *              @OA\Property(property="to", type="object", example={"id":"","name":"","email":"","phone":"","image":""}),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        return CreditResource::collection(
            QueryBuilder::for(Credit::class)
                ->allowedFilters([
                    AllowedFilter::exact('category_id'),
                    AllowedFilter::exact('subcategory_id'),
                    AllowedFilter::exact('credit_status_id'),
                    AllowedFilter::scope('created_at')
                ])
                ->whereHasMorph('userable', [Client::class], function ($q) use ($request) {
                    $q->where('vendor_id', $request->vendor_id);
                })
                ->where('userable_from_type', Vendor::class)
                ->where('userable_from_id', $request->vendor_id)
                ->paginate()
        );
    }

    /**
     * @OA\Post(
     *  path="/api/vendors/clients_credits/{id}/accept",
     *  summary="Vendor Accept Clients Credits",
     *  description="Vendor Accept Clients Credits",
     *  operationId="VendorAcceptClientsCredits",
     *  tags={"VendorClientsCredits"},
     *  security={{"bearerAuth": {}}},
     *  @OA\Parameter(
     *     name="id",
     *     description="clients credit request id",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *         type="integer"
     *     )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Error",
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
    public function accept(Request $request, $id)
    {
        $Credit = Credit::where('id', $id)->first();
        if (!$Credit) {
            return response()->json([
                "message" => "Wrong Credit id",
                "errors" => [
                    "id" => [
                        "Wrong Credit id",
                    ]
                ]
            ], 422);
        }
        if($Credit->userable_type == Client::class){
            $Client = Client::where('id', $Credit->userable_id)->first();
            if($Client){
                $credit_before = $Client->credit;
                $credit_after = $Client->credit + $Credit->amount;
            } else {
                return response()->json([
                    "message" => "Cant Approve Credits For Other Vendors",
                    "errors" => [
                        "id" => [
                            "Cant Approve Credits For Other Vendors",
                        ]
                    ]
                ], 422);
            }
        }else{
            return response()->json([
                "message" => "Cant Approve Credits For Other Vendors",
                "errors" => [
                    "id" => [
                        "Cant Approve Credits For Other Vendors",
                    ]
                ]
            ], 422);
        }
        if($Credit->userable_from_type == Vendor::class){
            $Vendor = Vendor::where('id', $Credit->userable_from_id)->first();
            if($Vendor){
                $credit_from_before = $Vendor->credit;
                $credit_from_after = $Vendor->credit - $Credit->amount;
                if($credit_from_after<0){
                    return response()->json([
                        "message" => "No Enough Credit",
                        "errors" => [
                            "id" => [
                                "No Enough Credit",
                            ]
                        ]
                    ], 422);
                }
            } else {
                return response()->json([
                    "message" => "Cant Approve Credits For Other Vendors",
                    "errors" => [
                        "id" => [
                            "Cant Approve Credits For Other Vendors",
                        ]
                    ]
                ], 422);
            }
        }else{
            return response()->json([
                "message" => "Cant Approve Credits For Other Vendors",
                "errors" => [
                    "id" => [
                        "Cant Approve Credits For Other Vendors",
                    ]
                ]
            ], 422);
        }
        $Client->update([
            "credit" => $credit_after
        ]);
        $Vendor->update([
            "credit" => $credit_from_after
        ]);
        $Credit->update([
            "credit_before" => $credit_before,
            "credit_after" => $credit_after,
            "credit_from_before" => $credit_from_before,
            "credit_from_after" => $credit_from_after,
            "credit_status_id" => 2
        ]);
        return response()->json(["data" => []], 200);
    }

    /**
     * @OA\Post(
     *  path="/api/vendors/clients_credits/{id}/reject",
     *  summary="Vendor Reject Clients Credits",
     *  description="Vendor Reject Clients Credits",
     *  operationId="VendorRejectClientsCredits",
     *  tags={"VendorClientsCredits"},
     *  security={{"bearerAuth": {}}},
     *  @OA\Parameter(
     *     name="id",
     *     description="clients credit request id",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *         type="integer"
     *     )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Error",
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
    public function reject(Request $request, $id)
    {
        $Credit = Credit::where('id', $id)->first();
        if (!$Credit) {
            return response()->json([
                "message" => "Wrong Credit id",
                "errors" => [
                    "id" => [
                        "Wrong Credit id",
                    ]
                ]
            ], 422);
        }
        if ($Credit->userable_type == Client::class) {
            $Client = Client::where('id', $Credit->userable_id)->first();
            if (!$Client) {
                return response()->json([
                    "message" => "Cant Reject Credits For Other Vendors",
                    "errors" => [
                        "id" => [
                            "Cant Reject Credits For Other Vendors",
                        ]
                    ]
                ], 422);
            }
        } else {
            return response()->json([
                "message" => "Cant Reject Credits For Other Vendors",
                "errors" => [
                    "id" => [
                        "Cant Reject Credits For Other Vendors",
                    ]
                ]
            ], 422);
        }
        if ($Credit->userable_from_type == Vendor::class) {
            $Vendor = Vendor::where('id', $Credit->userable_from_id)->first();
            if (!$Vendor) {
                return response()->json([
                    "message" => "Cant Approve Credits For Other Vendors",
                    "errors" => [
                        "id" => [
                            "Cant Approve Credits For Other Vendors",
                        ]
                    ]
                ], 422);
            }
        } else {
            return response()->json([
                "message" => "Cant Approve Credits For Other Vendors",
                "errors" => [
                    "id" => [
                        "Cant Approve Credits For Other Vendors",
                    ]
                ]
            ], 422);
        }
        $Credit->update([
            "credit_before" => 0,
            "credit_before" => 0,
            "credit_after" => 0,
            "credit_from_before" => 0,
            "credit_from_after" => 0,
            "credit_status_id" => 3
        ]);
        return response()->json(["data" => []], 200);
    }

    /**
     * @OA\Post(
     *  path="/api/vendors/clients_credits",
     *  summary="Vendor Add Clients Credits",
     *  description="Vendor Add Clients Credits",
     *  operationId="VendorAddClientsCredits",
     *  tags={"VendorClientsCredits"},
     *  security={{"bearerAuth": {}}},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"amount","client_id"},
     *         @OA\Property(property="amount", type="file", format="file"),
     *         @OA\Property(property="notes", type="string", example=""),
     *         @OA\Property(property="client_id", type="string", example=""),
     *       ),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Error",
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
    public function add(ClientAddCreditRequest $request, $id)
    {
        $Client = Client::where('id', $request->client_id)->where('vendor_id', $request->vendor_id)->first();
        if ($Client) {
            $credit_before = $Client->credit;
            $credit_after = $Client->credit + $request->amount;
        } else {
            return response()->json([
                "message" => "Cant Approve Credits For Other Vendors",
                "errors" => [
                    "id" => [
                        "Cant Approve Credits For Other Vendors",
                    ]
                ]
            ], 422);
        }

        $Vendor = Vendor::where('id', $request->vendor_id)->first();
        if ($Vendor) {
            $credit_from_before = $Vendor->credit;
            $credit_from_after = $Vendor->credit - $request->amount;
            if ($credit_from_after < 0) {
                return response()->json([
                    "message" => "No Enough Credit",
                    "errors" => [
                        "id" => [
                            "No Enough Credit",
                        ]
                    ]
                ], 422);
            }
        } else {
            return response()->json([
                "message" => "Cant Approve Credits For Other Vendors",
                "errors" => [
                    "id" => [
                        "Cant Approve Credits For Other Vendors",
                    ]
                ]
            ], 422);
        }
        if (Credit::create([
            "amount" => $request->amount,
            "notes" => $request->notes,
            "deposit_or_withdraw" => 0,
            "credit_type_id" => 1,
            "credit_before" => $credit_before,
            "credit_after" => $credit_after,
            "credit_status_id" => 2,
            "userable_type" => Client::class,
            "userable_id" => $request->client_id,
            "userable_from_type" => Vendor::class,
            "userable_from_id" => $request->vendor_id,
            "credit_from_before" => $credit_from_before,
            "credit_from_after" => $credit_from_after,
        ])) {
            return response()->json(["data" => []], 200);
        } else {
            return response()->json([
                "message" => "Error Requesting Credit",
                "errors" => [
                    "amount" => [
                        "Error Requesting Credit",
                    ]
                ]
            ], 422);
        }
    }
}
