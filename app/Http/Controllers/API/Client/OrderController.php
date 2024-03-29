<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\OrderRequest;
use App\Http\Requests\Client\OrderUploadRequest;
use App\Http\Requests\General\PrepaidCardPrintRequest;
use App\Http\Resources\Client\OrderResource;
use App\Models\Client;
use App\Models\FieldsAnswer;
use App\Models\Order;
use App\Models\OrderPrepaidCardStock;
use App\Models\PrepaidCardStock;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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
     *  @OA\Parameter(
     *    name="filter[category_id]",
     *    in="query",
     *    description="Filter Orders by category_id",
     *    example="1",
     *    required=false,
     *    @OA\Schema(type="int")
     *  ),
     *  @OA\Parameter(
     *    name="filter[subcategory_id]",
     *    in="query",
     *    description="Filter Orders by subcategory_id",
     *    example="1",
     *    required=false,
     *    @OA\Schema(type="int")
     *  ),
     *  @OA\Parameter(
     *    name="filter[created_at]",
     *    in="query",
     *    description="Filter Orders by created_at date range",
     *    example="2018-01-01,2018-12-31",
     *    required=false,
     *    @OA\Schema(type="string")
     *  ),
     *  @OA\Parameter(
     *    name="filter[order_status_id]",
     *    in="query",
     *    description="Filter Orders by order_status_id",
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
     *              @OA\Property(property="price", type="integer", example=""),
     *              @OA\Property(property="device_name", type="string", example=""),
     *              @OA\Property(property="created_at", type="string", example=""),
     *              @OA\Property(property="type", type="object", example={"id":"","name":"","image":"","need_approval":"","need_approval_message":""}),
     *              @OA\Property(property="category", type="object", example={"id":"","name":"","image":"","order":""}),
     *              @OA\Property(property="subcategory", type="object", example={"id":"","name":"","image":""}),
     *              @OA\Property(property="order_status", type="object", example={"id":"","name":""}),
     *              @OA\Property(property="product", type="object",
     *                  example={"id":"","name":"","description":"","unavailable_notes":"","how_to_use":"","image":"","suggested_price":"","price":"","stock":"","is_vip":"","type":{"id":"","name":"","image":"","need_approval":"","need_approval_message":""},"category":{"id":"","name":"","image":"","order":""},"subcategory":{"id":"","name":"","image":""},"fields":{"id":"","name":"","field_type":"","is_confirmed":"","answer":""}}
     *              ),
     *              @OA\Property(property="prepaid_cards", type="array",
     *                  @OA\Items(
     *                    example={"id":"","serial1":"","serial2":"","number1":"","number2":"","cvc":"","expiration_date":"","is_printed":""}
     *                  )
     *              ),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        return OrderResource::collection(
            QueryBuilder::for(Order::class)
                ->allowedFilters([
                    AllowedFilter::exact('category_id'),
                    AllowedFilter::exact('subcategory_id'),
                    AllowedFilter::exact('order_status_id'),
                    AllowedFilter::scope('created_at'),
                ])
                ->where('userable_type', Client::class)
                ->where('userable_id', $request->client_id)
                ->paginate()
        );
    }

    /**
     * @OA\Post(
     *  path="/api/clients/orders",
     *  summary="Client Create Order",
     *  description="Client Create Order",
     *  operationId="ClientCreateOrder",
     *  tags={"ClientOrders"},
     *  security={{"bearerAuth": {}}},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"quantity","device_name","product_id","fields"},
     *         @OA\Property(property="quantity", type="integer", example=""),
     *         @OA\Property(property="device_name", type="string", example=""),
     *         @OA\Property(property="product_id", type="integer", example=""),
     *         @OA\Property(property="is_printed", type="boolean", example=""),
     *         @OA\Property(
     *           property="fields",
     *           type="array",
     *           description="An array of items",
     *           @OA\Items(
     *             type="object",
     *             @OA\Property(property="answer", type="string"),
     *             @OA\Property(property="field_id", type="integer")
     *           )
     *         )
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
    public function store(OrderRequest $request)
    {
        $Client = Client::where("id", $request->client_id)->first();
        $Product = Product::where("id", $request->product_id)->first();
        if ($Product) {
            if ($Product->type_id == 1) {
                $price = $Product->selling_price * $request->quantity;
                $cost_price = $Product->cost_price * $request->quantity;
            } else {
                $price = $Product->selling_price;
                $cost_price = $Product->cost_price;
            }
            if ($Client->credit >= $price) {
                if ($Product->type_id == 1) {
                    if ($Product->stock_limit < $request->quantity) {
                        return response()->json([
                            "message" => "Cannot Buy More Than $Product->stock_limit",
                            "errors" => [
                                "product_id" => [
                                    "Cannot Buy More Than $Product->stock_limit",
                                ]
                            ]
                        ], 422);
                    }
                    $PrepaidCardStockCount = PrepaidCardStock::doesnthave('orderPrepaidCardStock')->where('product_id', $Product->id)->count();
                    if ($request->quantity > $PrepaidCardStockCount) {
                        return response()->json([
                            "message" => "Cannot Buy More Than " . $PrepaidCardStockCount,
                            "errors" => [
                                "product_id" => [
                                    "Cannot Buy More Than " . $PrepaidCardStockCount,
                                ]
                            ]
                        ], 422);
                    }
                }
                if ($Order = Order::create([
                    "quantity" => $request->quantity,
                    "device_name" => $request->device_name,
                    "product_id" => $request->product_id,
                    "type_id" => $Product->type_id,
                    "category_id" => $Product->category_id,
                    "subcategory_id" => $Product->subcategory_id,
                    "price" => $price,
                    "profit" => ($price - $cost_price),
                    "credit_before" => $Client->credit,
                    "credit_after" => ($Client->credit - $price),
                    "order_status_id" => 1,
                    "userable_type" => Client::class,
                    "userable_id" => $request->client_id,
                ])) {
                    if (count(json_decode($request->fields)) > 0) {
                        foreach (json_decode($request->fields) as $answer) {
                            FieldsAnswer::create([
                                'answer' => $answer->answer,
                                'field_id' => $answer->field_id,
                                'order_id' => $Order->id,
                                'product_id' => $Order->product_id,
                            ]);
                        }
                    }
                    if ($Order->type_id == 1) {
                        for ($i = 0; $i < $request->quantity; $i++) {
                            OrderPrepaidCardStock::create([
                                "is_printed" => $request->is_printed == "false" ? 0 : 1,
                                "order_id" => $Order->id,
                                "prepaid_card_stock_id" => PrepaidCardStock::doesnthave('orderPrepaidCardStock')->where('product_id', $Order->product_id)->orderBy('expiration_date')->first()->id,
                            ]);
                        }
                    }
                    $Client->update([
                        "credit" => ($Client->credit - $price)
                    ]);
                    return response()->json(["data" => new OrderResource(
                        Order::where('userable_type', Client::class)
                            ->where('userable_id', $request->client_id)
                            ->where('id', $Order->id)
                            ->first()
                    )], 200);
                } else {
                    return response()->json([
                        "message" => "SQL Error",
                        "errors" => [
                            "product_id" => [
                                "SQL Error",
                            ]
                        ]
                    ], 422);
                }
            } else {
                return response()->json([
                    "message" => "Balance is not enough",
                    "errors" => [
                        "product_id" => [
                            "Balance is not enough",
                        ]
                    ]
                ], 422);
            }
        } else {
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

    /**
     * @OA\Put(
     *  path="/api/clients/orders/upload",
     *  summary="Client Order Upload Image",
     *  description="Client Order Upload Image",
     *  operationId="ClientOrderUploadImage",
     *  tags={"ClientOrders"},
     *  security={{"bearerAuth": {}}},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"image"},
     *         @OA\Property(property="image", type="file", format="file"),
     *       ),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="image_path", type="string", example="")
     *    )
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
    public function upload(OrderUploadRequest $request)
    {
        $value = $request->image;
        $destination_path = "public/uploads";
        $image = Image::make($value)->encode('png', 90);
        $filename = md5($value . time()) . '.png';
        Storage::put($destination_path . '/' . $filename, $image->stream());
        $public_destination_path = Str::replaceFirst('public/', 'storage/', $destination_path);
        return response()->json(["data" => ['image_path' => ($public_destination_path . '/' . $filename)]], 200);
    }

    /**
     * @OA\Put(
     *  path="/api/clients/orders/print",
     *  summary="Client Order Print Card",
     *  description="Client Order Print Card",
     *  operationId="ClientOrderPrintCard",
     *  tags={"ClientOrders"},
     *  security={{"bearerAuth": {}}},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"card_ids"},
     *          @OA\Property(property="card_ids", type="array", @OA\Items(type="integer")),
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
    public function print(PrepaidCardPrintRequest $request)
    {
        foreach ($request->card_ids as $id) {
            $OrderPrepaidCardStock = OrderPrepaidCardStock::where('id', $id)->first();
            $OrderPrepaidCardStock->update([
                'is_printed' => 1
            ]);
        }
        return response()->json(["data" => []], 200);
    }
}
