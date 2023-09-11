<?php

namespace App\Http\Controllers\API\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\IssueTypeResource;
use App\Models\IssueType;
use Illuminate\Http\Request;

class IssueTypeController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/issueTypes",
     *  summary="IssueType",
     *  description="IssueType",
     *  operationId="IssueType",
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
    public function index()
    {
        return IssueTypeResource::collection(
            IssueType::get()
        );
    }
}
