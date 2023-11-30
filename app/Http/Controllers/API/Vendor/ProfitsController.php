<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\ProfitsResource;
use App\Models\VendorProfit;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @OA\Tag(
 *     name="VendorProfits",
 *     description="API Endpoints of Vendor Profits"
 * )
 */
class ProfitsController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/vendors/profits",
     *  summary="Vendor Profits",
     *  description="Vendor Profits",
     *  operationId="VendorProfits",
     *  tags={"VendorProfits"},
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
     *              @OA\Property(property="amount", type="integer", example=""),
     *              @OA\Property(property="notes", type="string", example=""),
     *              @OA\Property(property="created_at", type="string", example=""),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        return ProfitsResource::collection(
            QueryBuilder::for(VendorProfit::class)
                ->where('vendor_id', $request->vendor_id)
                ->paginate()
        );
    }
}
