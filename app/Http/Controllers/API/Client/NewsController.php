<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="ClientNews",
 *     description="API Endpoints of Client News"
 * )
 */
class NewsController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/clients/news",
     *  summary="News",
     *  description="Client News",
     *  operationId="ClientNews",
     *  tags={"ClientNews"},
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
     *              @OA\Property(property="title", type="string", example=""),
     *              @OA\Property(property="description", type="string", example=""),
     *              @OA\Property(property="action", type="string", example=""),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        return NewsResource::collection(News::orderBy('id', 'desc')->paginate());
    }
}
