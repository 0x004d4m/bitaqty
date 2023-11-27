<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\CreditPrepaidRequset;
use App\Http\Requests\General\CreditRequestRequset;
use App\Http\Resources\General\CreditResource;
use App\Models\Vendor;
use App\Models\Credit;
use App\Models\CreditCard;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @OA\Tag(
 *     name="VendorCredits",
 *     description="API Endpoints of Vendor Credits"
 * )
 */
class CreditController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/vendors/credits",
     *  summary="Credits",
     *  description="Vendor Credits",
     *  operationId="VendorCredits",
     *  tags={"VendorCredits"},
     *  security={{"bearerAuth": {}}},
     *  @OA\Parameter(
     *    name="filter[created_at]",
     *    in="query",
     *    description="Filter credits by created_at date range",
     *    example="2018-01-01,2018-12-31",
     *    required=false,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(
     *    name="filter[deposit_or_withdraw]",
     *    in="query",
     *    description="Filter credits by deposit_or_withdraw",
     *    required=false,
     *    @OA\Schema(type="int")
     *  ),
     *  @OA\Parameter(
     *    name="filter[credit_type_id]",
     *    in="query",
     *    description="Filter credits by credit_type_id",
     *    required=false,
     *    @OA\Schema(type="int")
     *  ),
     *  @OA\Parameter(
     *    name="filter[credit_status_id]",
     *    in="query",
     *    description="Filter credits by credit_status_id",
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
     *              @OA\Property(property="from", type="object", example={"name":"","email":"","phone":"","image":""}),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        $vendor_id = $request->vendor_id;
        return CreditResource::collection(
            QueryBuilder::for(Credit::class)
                ->allowedFilters([
                    AllowedFilter::scope('created_at'),
                    AllowedFilter::exact('deposit_or_withdraw'),
                    AllowedFilter::exact('credit_type_id'),
                    AllowedFilter::exact('credit_status_id'),
                ])->with([
                    'creditType',
                    'supportedAccount',
                    'creditStatus',
                    'userableFrom',
                ])->where(function ($q) use ($vendor_id) {
                    $q->where('userable_type', 'App\Models\Vendor')->where('userable_id', $vendor_id);
                })->orWhere(function ($q) use ($vendor_id) {
                    $q->where('userable_from_type', 'App\Models\Vendor')->where('userable_from_id', $vendor_id);
                })->paginate()
        );
    }

    /**
     * @OA\Post(
     *  path="/api/vendors/credits/request",
     *  summary="Vendor Credits Request",
     *  description="Vendor Credits Request",
     *  operationId="VendorCreditsRequest",
     *  tags={"VendorCredits"},
     *  security={{"bearerAuth": {}}},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"image","amount","notes","supported_account_id"},
     *         @OA\Property(property="image", type="file", format="file"),
     *         @OA\Property(property="amount", type="string", example=""),
     *         @OA\Property(property="notes", type="string", example=""),
     *         @OA\Property(property="supported_account_id", type="string", example=""),
     *       ),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
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
    public function request(CreditRequestRequset $request)
    {
        if (Credit::where("userable_type", 'App\Models\Vendor')->where("userable_id", $request->vendor_id)->where('credit_status_id', 1)->where('credit_type_id', 1)->count() == 0) {
            $Vendor = Vendor::where('id', $request->vendor_id)->first();
            if($Vendor){
                $balance = 0;
                $balance = $Vendor->credit + $request->amount;
                if (Credit::create([
                    "image" => $request->image,
                    "amount" => $request->amount,
                    "notes" => $request->notes,
                    "deposit_or_withdraw" => 0,
                    "credit_type_id" => 1,
                    "supported_account_id" => $request->supported_account_id,
                    "credit_before" => $Vendor->credit,
                    "credit_after" => $balance,
                    "credit_status_id" => 1,
                    "userable_type" => 'App\Models\Vendor',
                    "userable_id" => $request->vendor_id,
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
        } else {
            return response()->json([
                "message" => "Cant Request Credit, There is 1 pending request already",
                "errors" => [
                    "amount" => [
                        "Cant request credit, There is 1 pending request already",
                    ]
                ]
            ], 422);
        }
    }

    /**
     * @OA\Get(
     *  path="/api/vendors/credits/qr/{number}",
     *  summary="Vendor Credits Top Up By QR",
     *  description="Vendor Credits Top Up By QR",
     *  operationId="VendorCreditsQR",
     *  tags={"VendorCredits"},
     *  security={{"bearerAuth": {}}},
     *  @OA\Parameter(
     *     name="number",
     *     description="QR number",
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
    public function qr(Request $request, $number)
    {
        $CreditCard = CreditCard::where('url', url("/api/clients/credits/qr/" . $number))->first();
        if (!$CreditCard) {
            return response()->json([
                "message" => "Wrong QR",
                "errors" => [
                    "qr" => [
                        "Wrong QR",
                    ]
                ]
            ], 422);
        } else {
            if ($CreditCard->is_used) {
                return response()->json([
                    "message" => "QR Used Before",
                    "errors" => [
                        "qr" => [
                            "QR Used Before",
                        ]
                    ]
                ], 422);
            }
        }
        $Vendor = Vendor::where('id', $request->vendor_id)->first();
        $balance = $Vendor->credit + $CreditCard->value;
        if (Credit::create([
            "amount" => $CreditCard->value,
            "notes" => 'QR Top Up',
            "deposit_or_withdraw" => 0,
            "credit_type_id" => 2,
            "credit_before" => $Vendor->credit,
            "credit_after" => $balance,
            "credit_status_id" => 2,
            "userable_type" => 'App\Models\Vendor',
            "userable_id" => $request->vendor_id,
        ])) {
            $Vendor->update([
                "credit" => $balance
            ]);
            return response()->json(["data" => []], 200);
        } else {
            return response()->json([
                "message" => "Error Adding Credit",
                "errors" => [
                    "to_vendor_email_or_phone" => [
                        "Error Adding Credit",
                    ]
                ]
            ], 422);
        }
    }

    /**
     * @OA\Post(
     *  path="/api/vendors/credits/prepaid",
     *  summary="Vendor Credits Prepaid Top Up",
     *  description="Vendor Credits Prepaid Top Up",
     *  operationId="VendorCreditsPrepaid",
     *  tags={"VendorCredits"},
     *  security={{"bearerAuth": {}}},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"number"},
     *         @OA\Property(property="number", type="string", example=""),
     *       ),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
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
    public function prepaid(CreditPrepaidRequset $request)
    {
        $CreditCard = CreditCard::where('number', $request->number)->first();
        if (!$CreditCard) {
            return response()->json([
                "message" => "Wrong Card Number",
                "errors" => [
                    "number" => [
                        "Wrong Card Number",
                    ]
                ]
            ], 422);
        } else {
            if ($CreditCard->is_used) {
                return response()->json([
                    "message" => "Card Used Before",
                    "errors" => [
                        "number" => [
                            "Card Used Before",
                        ]
                    ]
                ], 422);
            }
        }
        $Vendor = Vendor::where('id', $request->vendor_id)->first();
        $balance = $Vendor->credit + $CreditCard->value;
        if (Credit::create([
            "amount" => $CreditCard->value,
            "notes" => "",
            "deposit_or_withdraw" => 0,
            "credit_type_id" => 3,
            "credit_before" => $Vendor->credit,
            "credit_after" => $balance,
            "credit_status_id" => 2,
            "userable_type" => 'App\Models\Vendor',
            "userable_id" => $request->vendor_id,
        ])) {
            $Vendor->update([
                "credit" => $balance
            ]);
            return response()->json(["data" => []], 200);
        } else {
            return response()->json([
                "message" => "Error Requesting Credit",
                "errors" => [
                    "number" => [
                        "Error Requesting Credit",
                    ]
                ]
            ], 422);
        }
    }
}
