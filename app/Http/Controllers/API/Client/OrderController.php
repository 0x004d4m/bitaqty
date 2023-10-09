<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\OrderRequest;
use App\Http\Requests\Client\OrderUploadRequest;
use App\Http\Resources\Client\OrderResource;
use App\Models\Client;
use App\Models\FieldsAnswer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

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
     *              @OA\Property(property="price", type="integer", example=""),
     *              @OA\Property(property="device_name", type="string", example=""),
     *              @OA\Property(property="created_at", type="string", example=""),
     *              @OA\Property(property="type", type="object", example={"id":"","name":"","image":"","need_approval":""}),
     *              @OA\Property(property="category", type="object", example={"id":"","name":"","image":"","order":""}),
     *              @OA\Property(property="subcategory", type="object", example={"id":"","name":"","image":""}),
     *              @OA\Property(property="order_status", type="object", example={"id":"","name":""}),
     *              @OA\Property(property="product", type="object",
     *                  example={"id":"","name":"","description":"","unavailable_notes":"","how_to_use":"","image":"","suggested_price":"","price":"","stock":"","is_vip":"","type":{"id":"","name":"","image":"","need_approval":""},"category":{"id":"","name":"","image":"","order":""},"subcategory":{"id":"","name":"","image":""},"fields":{"id":"","name":"","field_type":"","is_confirmed":"","answer":""}}
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
            Order::where('userable_type', 'App\Models\Client')
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
        if($Product){
            if($Client->credit >= $Product->selling_price){
                if($Order = Order::create([
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
                ])){
                    if(count($request->fields) > 0){
                        foreach($request->fields as $answer){
                            FieldsAnswer::create([
                                'answer' => $answer->answer,
                                'field_id' => $request->field_id,
                                'order_id' => $Order->id,
                                'product_id' => $Order->product_id,
                            ]);
                        }
                    }
                    return response()->json(["data" => []], 200);
                }else{
                    return response()->json([
                        "message" => "SQL Error",
                        "errors" => [
                            "product_id" => [
                                "SQL Error",
                            ]
                        ]
                    ], 422);
                }
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
        return response()->json(["data" => ['image_path'=> ($public_destination_path . '/' . $filename)]], 200);
    }
}
