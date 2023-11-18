<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="ClientMaintenance",
 *     description="API Endpoints of Clients Maintenance"
 * )
 */
class MaintenanceController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/clients/maintenance",
     *  summary="Maintenance",
     *  description="Client Maintenance",
     *  operationId="ClientMaintenance",
     *  tags={"ClientMaintenance"},
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
