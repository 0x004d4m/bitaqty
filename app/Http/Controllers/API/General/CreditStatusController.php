<?php

namespace App\Http\Controllers\API\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\CreditStatusResource;
use App\Models\CreditStatus;
use Illuminate\Http\Request;

class CreditStatusController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/creditStatuses",
     *  summary="CreditStatuses",
     *  description="CreditStatuses",
     *  operationId="CreditStatuses",
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
        return CreditStatusResource::collection(CreditStatus::get());
    }
}
