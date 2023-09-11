<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Issue;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="ClientIssue",
 *     description="API Endpoints of Clients Issues"
 * )
 */
class IssueController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/clients/issues",
     *  summary="Issues",
     *  description="Client Issues",
     *  operationId="ClientIssues",
     *  tags={"ClientIssue"},
     *  security={{"bearerAuth": {}}},
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(
     *          property="countries",
     *          type="array",
     *          @OA\Items(
     *              @OA\Property(property="id", type="integer", example=""),
     *              @OA\Property(property="description", type="string", example=""),
     *              @OA\Property(property="image", type="string", example=""),
     *              @OA\Property(property="solution", type="string", example=""),
     *              @OA\Property(property="is_solved", type="boolean", example=""),
     *              @OA\Property(property="is_duplicate", type="boolean", example=""),
     *              @OA\Property(property="issue_type_id", type="boolean", example=""),
     *              @OA\Property(property="issue_type", type="object", example={"id":1,"name":""}),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        // "description",
        // "image",
        // "solution",
        // "is_solved",
        // "is_duplicate",
        // "issue_type_id",
        // "userable_type",
        // "userable_id",
        return response()->json(
            Issue::with(['issueType'])->where('userable_type', Client::class)
                ->where('userable_id', $request->client_id)
                ->paginate()
        , 200);
    }

    public function store()
    {

    }
}
