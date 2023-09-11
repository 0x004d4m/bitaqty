<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Issues\CreateIssueRequest;
use App\Http\Resources\General\CountryResource;
use App\Http\Resources\General\IssueResource;
use App\Models\Client;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

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
     *          property="data",
     *          type="array",
     *          @OA\Items(
     *              @OA\Property(property="id", type="integer", example=""),
     *              @OA\Property(property="description", type="string", example=""),
     *              @OA\Property(property="image", type="string", example=""),
     *              @OA\Property(property="solution", type="string", example=""),
     *              @OA\Property(property="is_solved", type="boolean", example=""),
     *              @OA\Property(property="is_duplicate", type="boolean", example=""),
     *              @OA\Property(property="issue_type", type="object", example={"id":1,"name":""}),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        return IssueResource::collection(
            Issue::with(['issueType'])
            ->where('userable_type', Client::class)
            ->where('userable_id', $request->client_id)
            ->paginate()
        );
    }

    /**
     * @OA\Post(
     *  path="/api/clients/issues",
     *  summary="Create Client Issue",
     *  description="Create Client Issue",
     *  operationId="ClientCreateIssue",
     *  tags={"ClientIssue"},
     *  security={{"bearerAuth": {}}},
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          required={"issue_type_id","description"},
     *         @OA\Property(property="issue_type_id", type="integer", example=""),
     *         @OA\Property(property="description", type="string", example=""),
     *         @OA\Property(property="image", type="file", format="file"),
     *       ),
     *     ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *      @OA\Property(property="message", type="string", example=""),
     *      @OA\Property(property="errors", type="object",
     *         @OA\Property(property="dynamic-error-keys", type="array",
     *           @OA\Items(type="string")
     *         )
     *       )
     *     )
     *  )
     * )
     */
    public function store(CreateIssueRequest $request)
    {
        if(Issue::create([
            "description" => $request->description,
            "image" => $request->image,
            "issue_type_id" => $request->issue_type_id,
            "userable_type" => Client::class,
            "userable_id" => $request->client_id,
        ])){
            return response()->json([],200);
        }else{
            return response()->json([
                "message" => "Error Creating Issue",
                "errors" => [
                    "description" => [
                        "Error Creating Issue",
                    ]
                ]
            ], 422);
        }
    }
}
