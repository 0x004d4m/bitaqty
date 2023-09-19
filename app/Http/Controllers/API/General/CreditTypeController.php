<?php

namespace App\Http\Controllers\API\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\CreditTypeResource;
use App\Models\CreditType;
use Illuminate\Http\Request;

class CreditTypeController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/creditTypes",
     *  summary="CreditTypes",
     *  description="CreditTypes",
     *  operationId="CreditTypes",
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
     *              @OA\Property(property="name", type="string", example=""),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        return CreditTypeResource::collection(CreditType::get());
    }
}
