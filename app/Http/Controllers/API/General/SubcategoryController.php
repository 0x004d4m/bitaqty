<?php

namespace App\Http\Controllers\API\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\SubcategoryResource;
use App\Models\Subcategory;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="GeneralSubcategories",
 *     description="API Endpoints of Subcategories"
 * )
 */
class SubcategoryController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/categories/{id}/subcategories",
     *  summary="Subcategories By Category",
     *  description="Subcategories By Category",
     *  operationId="Subcategories",
     *  tags={"GeneralSubcategories"},
     *  @OA\Parameter(
     *     name="id",
     *     description="Category id",
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
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request, $id)
    {
        return SubcategoryResource::collection(
            Subcategory::where('category_id',$id)->where('is_active', 1)->get()
        );
    }
}
