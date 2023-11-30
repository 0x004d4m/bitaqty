<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Client\OrderResource;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @OA\Tag(
 *     name="VendorClientsOrders",
 *     description="API Endpoints of Vendor Clients Orders"
 * )
 */
class ClientsOrdersController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/vendors/clients_orders",
     *  summary="Vendor Clients Orders",
     *  description="Vendor Clients Orders",
     *  operationId="VendorClientsOrders",
     *  tags={"VendorClientsOrders"},
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
     *    name="filter[id]",
     *    in="query",
     *    description="Filter Orders by id",
     *    example="1",
     *    required=false,
     *    @OA\Schema(type="int")
     *  ),
     *  @OA\Parameter(
     *    name="filter[product_id]",
     *    in="query",
     *    description="Filter Orders by product_id",
     *    example="1",
     *    required=false,
     *    @OA\Schema(type="int")
     *  ),
     *  @OA\Parameter(
     *    name="filter[order_status_id]",
     *    in="query",
     *    description="Filter Orders by order_status_id",
     *    example="1",
     *    required=false,
     *    @OA\Schema(type="int")
     *  ),
     *  @OA\Parameter(
     *    name="filter[client_phone]",
     *    in="query",
     *    description="Filter Orders by client_phone",
     *    example="1",
     *    required=false,
     *    @OA\Schema(type="int")
     *  ),
     *  @OA\Parameter(
     *    name="filter[card_number_1]",
     *    in="query",
     *    description="Filter Orders by card_number_1",
     *    example="1",
     *    required=false,
     *    @OA\Schema(type="int")
     *  ),
     *  @OA\Parameter(
     *    name="filter[card_number_2]",
     *    in="query",
     *    description="Filter Orders by card_number_2",
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
                    AllowedFilter::exact('id'),
                    AllowedFilter::exact('category_id'),
                    AllowedFilter::exact('subcategory_id'),
                    AllowedFilter::exact('order_status_id'),
                    AllowedFilter::exact('product_id'),
                    AllowedFilter::scope('created_at'),
                    AllowedFilter::callback('client_phone', function ($query, $value) {
                        $query->whereHasMorph('userable', [Client::class], function ($q) use ($value) {
                            $q->where('phone', $value);
                        });
                    }),
                    AllowedFilter::callback('card_number_1', function ($query, $value) {
                        $query->whereHas('orderPrepaidCardStocks', [Client::class], function ($q) use ($value) {
                            $q->whereHas('prepaidCardStock', function ($qq) use ($value) {
                                $qq->where('number1', $value);
                            });
                        });
                    }),
                    AllowedFilter::callback('card_number_2', function ($query, $value) {
                        $query->whereHas('orderPrepaidCardStocks', [Client::class], function ($q) use ($value) {
                            $q->whereHas('prepaidCardStock', function ($qq) use ($value) {
                                $qq->where('number2', $value);
                            });
                        });
                    })
                ])
                ->whereHasMorph('userable', [Client::class], function ($q) use($request) {
                    $q->where('vendor_id', $request->vendor_id);
                })
                ->paginate()
        );
    }
}
