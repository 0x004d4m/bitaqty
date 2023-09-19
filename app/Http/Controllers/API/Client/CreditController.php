<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\CreditRequestRequset;
use App\Http\Requests\Client\CreditSendRequset;
use App\Http\Resources\Client\CreditResource;
use App\Models\Client;
use App\Models\Credit;
use Illuminate\Http\Request;

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
        return CreditResource::collection(
            Credit::with([
                'creditType',
                'supportedAccount',
                'creditStatus',
                'userableFrom',
            ])
                ->where('userable_type', 'App\Models\Client')
                ->where('userable_id', $request->client_id)
                ->paginate()
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
     *          required={"image","amount","notes","deposit_or_withdraw","supported_account_id"},
     *         @OA\Property(property="image", type="string", example=""),
     *         @OA\Property(property="amount", type="string", example=""),
     *         @OA\Property(property="notes", type="string", example=""),
     *         @OA\Property(property="deposit_or_withdraw", type="string", example=""),
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
        $Client = Client::where('id', $request->client_id)->first();
        $balance = 0;
        if ($request->deposit_or_withdraw) {
            $balance = $Client->credit + $request->amount;
        } else {
            $balance = $Client->credit - $request->amount;
        }
        if (Credit::create([
            "image" => $request->image,
            "amount" => $request->amount,
            "notes" => $request->notes,
            "deposit_or_withdraw" => $request->deposit_or_withdraw,
            "credit_type_id" => 1,
            "supported_account_id" => $request->supported_account_id,
            "credit_before" => $Client->credit,
            "credit_after" => $balance,
            "credit_status_id" => 1,
            "userable_type" => 'App\Models\Client',
            "userable_id" => $request->client_id,
            "userable_type" => 'App\Models\Client',
            "userable_id" => $request->client_id,
        ])) {
            return response()->json([], 200);
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
        $balanceTo = 0;
        if ($request->deposit_or_withdraw) {
            $balanceTo = $To->credit + $request->amount;
        } else {
            $balanceTo = $To->credit - $request->amount;
        }

        $From = Client::where('id', $request->client_id)->first();
        $balanceFrom = 0;
        if ($request->deposit_or_withdraw) {
            $balanceFrom = $From->credit - $request->amount;
        } else {
            $balanceFrom = $From->credit + $request->amount;
        }
        if (Credit::create([
            "amount" => $request->amount,
            "notes" => $request->notes,
            "deposit_or_withdraw" => 0,
            "credit_type_id" => 4,
            "credit_before" => $From->credit,
            "credit_after" => $balanceFrom,
            "credit_status_id" => 1,
            "userable_type" => 'App\Models\Client',
            "userable_id" => $request->client_id,
            "userable_from_type" => 'App\Models\Client',
            "userable_from_id" => $To->client_id,
            "credit_from_before" => $To->credit,
            "credit_from_after" => $balanceTo,
        ])) {
            return response()->json([], 200);
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
}
