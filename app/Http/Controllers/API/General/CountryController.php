<?php

namespace App\Http\Controllers\API\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="General",
 *     description="API Endpoints of General Info"
 * )
 */
class CountryController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/countries",
     *  summary="Countries",
     *  description="Countries",
     *  operationId="Countries",
     *  tags={"General"},
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(
     *          property="data",
     *          type="array",
     *          @OA\Items(
     *              @OA\Property(property="id", type="integer", example=""),
     *              @OA\Property(property="code", type="string", example=""),
     *              @OA\Property(property="name", type="string", example=""),
     *              @OA\Property(
     *                  property="states",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example=""),
     *                      @OA\Property(property="name", type="string", example=""),
     *                  ),
     *              ),
     *              @OA\Property(
     *                  property="currencies",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example=""),
     *                      @OA\Property(property="name", type="string", example=""),
     *                      @OA\Property(property="symbol", type="string", example=""),
     *                      @OA\Property(property="to_jod", type="string", example=""),
     *                  ),
     *              ),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index()
    {
        return CountryResource::collection(
            Country::with([
                'states',
                'currencies',
            ])->get()
        );
    }
}
