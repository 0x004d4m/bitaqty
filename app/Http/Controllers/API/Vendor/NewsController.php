<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="VendorNews",
 *     description="API Endpoints of Vendor News"
 * )
 */
class NewsController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/vendors/news",
     *  summary="News",
     *  description="Vendor News",
     *  operationId="VendorNews",
     *  tags={"VendorNews"},
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
     *              @OA\Property(property="action", type="string", example=""),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        return NewsResource::collection(News::where('type','vendor')->orderBy('id', 'desc')->get());
    }
}
