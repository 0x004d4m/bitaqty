<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\StatisticsResource;
use App\Models\Vendor;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/vendors/statistics",
     *  summary="Statistics",
     *  description="Vendor Statistics",
     *  operationId="VendorStatistics",
     *  tags={"VendorStatistics"},
     *  security={{"bearerAuth": {}}},
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="clients", type="integer", example=""),
     *      @OA\Property(property="clients_orders", type="integer", example=""),
     *      @OA\Property(property="clients_credit_pending_requests", type="integer", example=""),
     *      @OA\Property(property="profit", type="integer", example=""),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        return new StatisticsResource(
            Vendor::where('id', $request->vendor_id)
                ->first()
        );
    }
}
