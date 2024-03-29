<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\CreditPrepaidRequset;
use App\Http\Requests\General\CreditRequestRequset;
use App\Http\Requests\Client\CreditSendRequset;
use App\Http\Resources\General\CreditResource;
use App\Models\Client;
use App\Models\Credit;
use App\Models\CreditCard;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @OA\Tag(
 *     name="ClientCredits",
 *     description="API Endpoints of Client Credits"
 * )
 */
class CreditController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/clients/credits",
     *  summary="Credits",
     *  description="Client Credits",
     *  operationId="ClientCredits",
     *  tags={"ClientCredits"},
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
        $client_id = $request->client_id;
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
                ])->where(function ($q) use ($client_id) {
                    $q->where('userable_type', Client::class)->where('userable_id', $client_id);
                })->orWhere(function ($q) use ($client_id) {
                    $q->where('userable_from_type', Client::class)->where('userable_from_id', $client_id);
                })->paginate()
        );
    }

    /**
     * @OA\Post(
     *  path="/api/clients/credits/request",
     *  summary="Client Credits Request",
     *  description="Client Credits Request",
     *  operationId="ClientCreditsRequest",
     *  tags={"ClientCredits"},
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
        if (Credit::where("userable_type", Client::class)->where("userable_id", $request->client_id)->where('credit_status_id', 1)->where('credit_type_id', 1)->count() == 0) {
            $Client = Client::where('id', $request->client_id)->first();
            $balance = 0;
            $balance = $Client->credit + $request->amount;
            $userable_from_type = null;
            $userable_from_id = null;
            if($Client->vendor){
                $userable_from_type = Vendor::class;
                $userable_from_id = $Client->vendor_id;
            }
            if (Credit::create([
                "image" => $request->image,
                "amount" => $request->amount,
                "notes" => $request->notes,
                "deposit_or_withdraw" => 0,
                "credit_type_id" => 1,
                "supported_account_id" => $request->supported_account_id,
                "credit_before" => $Client->credit,
                "credit_after" => $balance,
                "credit_status_id" => 1,
                "userable_type" => Client::class,
                "userable_id" => $request->client_id,
                "userable_from_type" => $userable_from_type,
                "userable_from_id" => $userable_from_id,
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
     * @OA\Post(
     *  path="/api/clients/credits/send",
     *  summary="Client Credits Send",
     *  description="Client Credits Send",
     *  operationId="ClientCreditsSend",
     *  tags={"ClientCredits"},
     *  security={{"bearerAuth": {}}},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"amount","notes","to_client_email_or_phone"},
     *         @OA\Property(property="amount", type="string", example=""),
     *         @OA\Property(property="notes", type="string", example=""),
     *         @OA\Property(property="to_client_email_or_phone", type="string", example=""),
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
    public function send(CreditSendRequset $request)
    {
        $To = Client::where('email', $request->to_client_email_or_phone)->first();
        if (!$To) {
            $To = Client::where('phone', $request->to_client_email_or_phone)->first();
            if (!$To) {
                return response()->json([
                    "message" => "Error Wrong Client Email Or Phone",
                    "errors" => [
                        "to_client_email_or_phone" => [
                            "Error Wrong Client Email Or Phone ",
                        ]
                    ]
                ], 422);
            }
        }
        $From = Client::where('id', $request->client_id)->first();
        if ($From->credit < $request->amount) {
            return response()->json([
                "message" => "Balance Not Enough",
                "amount" => [
                    "to_client_email_or_phone" => [
                        "Balance Not Enough",
                    ]
                ]
            ], 422);
        }

        $balanceTo = $To->credit + $request->amount;
        $balanceFrom = $From->credit - $request->amount;
        if (Credit::create([
            "amount" => $request->amount,
            "notes" => $request->notes,
            "deposit_or_withdraw" => 0,
            "credit_type_id" => 4,
            "credit_before" => $To->credit,
            "credit_after" => $balanceTo,
            "credit_status_id" => 2,
            "userable_type" => Client::class,
            "userable_id" => $To->id,
            "userable_from_type" => Client::class,
            "userable_from_id" => $From->id,
            "credit_from_before" => $From->credit,
            "credit_from_after" => $balanceFrom,
        ])) {
            $To->update([
                "credit" => $balanceTo,
            ]);
            $From->update([
                "credit" => $balanceFrom,
            ]);
            return response()->json(["data" => []], 200);
        } else {
            return response()->json([
                "message" => "Error Requesting Credit",
                "errors" => [
                    "to_client_email_or_phone" => [
                        "Error Requesting Credit",
                    ]
                ]
            ], 422);
        }
    }

    /**
     * @OA\Get(
     *  path="/api/clients/credits/qr/{number}",
     *  summary="Client Credits Top Up By QR",
     *  description="Client Credits Top Up By QR",
     *  operationId="ClientCreditsQR",
     *  tags={"ClientCredits"},
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
        $Client = Client::where('id', $request->client_id)->first();
        $balance = $Client->credit + $CreditCard->value;
        if (Credit::create([
            "amount" => $CreditCard->value,
            "notes" => 'QR Top Up',
            "deposit_or_withdraw" => 0,
            "credit_type_id" => 2,
            "credit_before" => $Client->credit,
            "credit_after" => $balance,
            "credit_status_id" => 2,
            "userable_type" => Client::class,
            "userable_id" => $request->client_id,
        ])) {
            $Client->update([
                "credit" => $balance
            ]);
            return response()->json(["data" => []], 200);
        } else {
            return response()->json([
                "message" => "Error Adding Credit",
                "errors" => [
                    "to_client_email_or_phone" => [
                        "Error Adding Credit",
                    ]
                ]
            ], 422);
        }
    }

    /**
     * @OA\Post(
     *  path="/api/clients/credits/prepaid",
     *  summary="Client Credits Prepaid Top Up",
     *  description="Client Credits Prepaid Top Up",
     *  operationId="ClientCreditsPrepaid",
     *  tags={"ClientCredits"},
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
        $Client = Client::where('id', $request->client_id)->first();
        $balance = $Client->credit + $CreditCard->value;
        if (Credit::create([
            "amount" => $CreditCard->value,
            "notes" => "",
            "deposit_or_withdraw" => 0,
            "credit_type_id" => 3,
            "credit_before" => $Client->credit,
            "credit_after" => $balance,
            "credit_status_id" => 2,
            "userable_type" => Client::class,
            "userable_id" => $request->client_id,
        ])) {
            $Client->update([
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
