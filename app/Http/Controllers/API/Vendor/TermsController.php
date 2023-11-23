<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\TermsResource;
use App\Models\Term;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="VendorTerms",
 *     description="API Endpoints of Vendor Terms"
 * )
 */
class TermsController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/vendors/privacy_policy",
     *  summary="terms",
     *  description="Vendor Terms",
     *  operationId="VendorTerms",
     *  tags={"VendorTerms"},
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
        return new TermsResource(Term::where('id', 3)->first());
    }

    /**
     * @OA\Get(
     *  path="/api/vendors/terms_and_conditions",
     *  summary="Privacy",
     *  description="Vendor Privacy",
     *  operationId="VendorPrivacy",
     *  tags={"VendorTerms"},
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
        return new TermsResource(Term::where('id', 4)->first());
    }
}
