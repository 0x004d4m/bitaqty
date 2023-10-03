<?php

namespace App\Http\Controllers\API\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\ProductResource;
use App\Http\Resources\General\ProductsResource;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="GeneralProducts",
 *     description="API Endpoints of Products"
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/subcategories/{id}/products",
     *  summary="Products By Subcategory",
     *  description="Products By Subcategory",
     *  operationId="Products",
     *  tags={"GeneralProducts"},
     *  security={{"bearerAuth": {}}},
     *  @OA\Parameter(
     *     name="id",
     *     description="Subcategory id",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *         type="integer"
     *     )
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
     *              @OA\Property(property="name", type="string", example=""),
     *              @OA\Property(property="description", type="string", example=""),
     *              @OA\Property(property="image", type="string", example=""),
     *              @OA\Property(property="price", type="float", example=""),
     *              @OA\Property(property="stock", type="integer", example=""),
     *              @OA\Property(property="is_vip", type="boolean", example=""),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request, $id)
    {
        return ProductsResource::collection(
            Product::where('subcategory_id',$id)->where('is_active', 1)->get()
        );
    }

    /**
     * @OA\Get(
     *  path="/api/products/{id}",
     *  summary="Product Details By id",
     *  description="Product Details By id",
     *  operationId="ProductDetails",
     *  tags={"GeneralProducts"},
     *  security={{"bearerAuth": {}}},
     *  @OA\Parameter(
     *     name="id",
     *     description="Product id",
     *     required=true,
     *     in="path",
     *     @OA\Schema(
     *         type="integer"
     *     )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="id", type="integer", example=""),
     *      @OA\Property(property="name", type="string", example=""),
     *      @OA\Property(property="description", type="string", example=""),
     *      @OA\Property(property="unavailable_notes", type="string", example=""),
     *      @OA\Property(property="how_to_use", type="string", example=""),
     *      @OA\Property(property="image", type="string", example=""),
     *      @OA\Property(property="price", type="float", example=""),
     *      @OA\Property(property="stock", type="integer", example=""),
     *      @OA\Property(property="is_vip", type="boolean", example=""),
     *      @OA\Property(property="fields", type="object", example={"id":0,"name":"","field_type":"","is_confirmed":0})
     *    ),
     *  )
     * )
     */
    public function show(Request $request, $id)
    {
        return ProductResource::collection(
            Product::where('subcategory_id',$id)->where('is_active', 1)->get()
        );
    }
}
