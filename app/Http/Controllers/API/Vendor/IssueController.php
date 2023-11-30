<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\IssueRequest;
use App\Http\Resources\General\IssueResource;
use App\Models\Issue;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="VendorIssue",
 *     description="API Endpoints of Vendors Issues"
 * )
 */
class IssueController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/vendors/issues",
     *  summary="Issues",
     *  description="Vendor Issues",
     *  operationId="VendorIssues",
     *  tags={"VendorIssue"},
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
            ->where('userable_type', Vendor::class)
            ->where('userable_id', $request->vendor_id)
            ->paginate()
        );
    }

    /**
     * @OA\Post(
     *  path="/api/vendors/issues",
     *  summary="Create Vendor Issue",
     *  description="Create Vendor Issue",
     *  operationId="VendorCreateIssue",
     *  tags={"VendorIssue"},
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
    public function store(IssueRequest $request)
    {
        if(Issue::create([
            "description" => $request->description,
            "image" => $request->image,
            "issue_type_id" => $request->issue_type_id,
            "userable_type" => Vendor::class,
            "userable_id" => $request->vendor_id,
        ])){
            return response()->json(["data" => []], 200);
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
