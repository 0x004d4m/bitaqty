<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\TermsResource;
use App\Models\Term;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="ClientTerms",
 *     description="API Endpoints of Client Terms"
 * )
 */
class TermsController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/clients/privacy_policy",
     *  summary="terms",
     *  description="Client Terms",
     *  operationId="ClientTerms",
     *  tags={"ClientTerms"},
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(
     *          property="data",
     *          type="object",
     *          example={"id":0,"name":"","term":""}
     *      ),
     *    ),
     *  )
     * )
     */
    public function privacy(Request $request)
    {
        return new TermsResource(Term::where('id', 1)->first());
    }

    /**
     * @OA\Get(
     *  path="/api/clients/terms_and_conditions",
     *  summary="Privacy",
     *  description="Client Privacy",
     *  operationId="ClientPrivacy",
     *  tags={"ClientTerms"},
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(
     *          property="data",
     *          type="object",
     *          example={"id":0,"name":"","term":""}
     *      ),
     *    ),
     *  )
     * )
     */
    public function terms(Request $request)
    {
        return new TermsResource(Term::where('id', 2)->first());
    }
}
