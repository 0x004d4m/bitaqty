<?php

namespace App\Http\Controllers\API\General;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Country",
 *     description="API Endpoints of Country"
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
     *  tags={"Country"},
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(
     *          property="countries",
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
     *                      @OA\Property(property="country_id", type="string", example=""),
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
     *                      @OA\Property(property="country_id", type="string", example=""),
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
        return response()->json([
            "countries" => Country::with([
                'states',
                'currencies',
            ])->get()
        ], 200);
    }
}
