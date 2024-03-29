<?php

namespace App\Http\Controllers\API\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\TypeResource;
use App\Models\Type;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="GeneralTypes",
 *     description="API Endpoints of Types"
 * )
 */
class TypeController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/types",
     *  summary="Types",
     *  description="Types",
     *  operationId="Types",
     *  tags={"GeneralTypes"},
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
     *              @OA\Property(property="name", type="string", example=""),
     *              @OA\Property(property="image", type="string", example=""),
     *              @OA\Property(property="need_approval", type="boolean", example=""),
     *              @OA\Property(property="need_approval_message", type="string", example=""),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        return TypeResource::collection(
            Type::where('is_active', 1)->get()
        );
    }
}
