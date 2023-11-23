<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="VendorMaintenance",
 *     description="API Endpoints of Vendors Maintenance"
 * )
 */
class MaintenanceController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/vendors/maintenance",
     *  summary="Maintenance",
     *  description="Vendor Maintenance",
     *  operationId="VendorMaintenance",
     *  tags={"VendorMaintenance"},
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(property="is_active", type="boolean", example=0)
     *    )
     *  ),
     * )
     */
    public function index(Request $request){
        return response()->json(["data"=>["is_active"=>Maintenance::first()->is_active]]);
    }
}
