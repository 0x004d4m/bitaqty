<?php

namespace App\Http\Controllers\API\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="GeneralCategories",
 *     description="API Endpoints of Categories"
 * )
 */
class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/types/{id}/categories",
     *  summary="Categories By Type",
     *  description="Categories By Type",
     *  operationId="Categories",
     *  tags={"GeneralCategories"},
     *  security={{"bearerAuth": {}}},
     *  @OA\Parameter(
     *     name="id",
     *     description="type id",
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
     *              @OA\Property(property="image", type="string", example=""),
     *              @OA\Property(property="order", type="integer", example=""),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request, $id)
    {
        return CategoryResource::collection(
            Category::where('type_id', $id)->where('country_id', $request->country_id)->where('state_id', $request->state_id)->where('is_active', 1)->get()
        );
    }
}
