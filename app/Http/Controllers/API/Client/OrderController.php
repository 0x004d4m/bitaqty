<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\OrderRequest;
use App\Http\Resources\Client\OrderResource;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="ClientOrders",
 *     description="API Endpoints of Client Orders"
 * )
 */
class OrderController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/clients/orders",
     *  summary="Orders",
     *  description="Orders",
     *  operationId="Orders",
     *  tags={"ClientOrders"},
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
     *              @OA\Property(property="category", type="integer", example=""),
     *              @OA\Property(property="subcategory", type="string", example=""),
     *              @OA\Property(property="image", type="string", example=""),
     *              @OA\Property(property="created_at", type="string", example=""),
     *              @OA\Property(property="order_status", type="object", example={"id":"","name":""}),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        return OrderResource::collection(
            Order::where('userable_type', 'App\Models\Client')
            ->where('userable_id', $request->client_id)
                ->paginate()
        );
    }

    public function store(OrderRequest $request)
    {
        $Client = Client::where("id", $request->client_id)->first();
        $Product = Product::where("id", $request->product_id)->first();
        if($Product){
            if($Client->credit >= $Product->selling_price){
                Order::create([
                    "quantity" => $request->quantity,
                    "device_name" => $request->device_name,
                    "product_id" => $request->product_id,
                    "price" => $Product->selling_price,
                    "profit" => ($Product->selling_price - $Product->cost_price),
                    "credit_before" => $Client->credit,
                    "credit_after" => ($Client->credit - $Product->selling_price),
                    "order_status_id" => 1,
                    "userable_type" => 'App\Models\Client',
                    "userable_id" => $request->client_id,
                ]);
            }else{
                return response()->json([
                    "message" => "Balance is not enough",
                    "errors" => [
                        "product_id" => [
                            "Balance is not enough",
                        ]
                    ]
                ], 422);
            }
        }else {
            return response()->json([
                "message" => "Invaled product id",
                "errors" => [
                    "product_id" => [
                        "Invaled product id",
                    ]
                ]
            ], 422);
        }
    }
}
