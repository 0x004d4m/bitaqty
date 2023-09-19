<?php

namespace App\Http\Controllers\API\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\SupportedAccountResource;
use App\Models\SupportedAccount;
use Illuminate\Http\Request;

class SupportedAccountController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/supportedAccounts",
     *  summary="SupportedAccounts",
     *  description="SupportedAccounts",
     *  operationId="SupportedAccounts",
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
     *              @OA\Property(property="image", type="string", example=""),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        return SupportedAccountResource::collection(SupportedAccount::where('is_active',1)->get());
    }
}
