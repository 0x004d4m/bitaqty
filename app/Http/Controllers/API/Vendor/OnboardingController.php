<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\OnboardingResource;
use App\Models\Onboarding;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="VendorOnboardings",
 *     description="API Endpoints of Vendor Onboardings"
 * )
 */
class OnboardingController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/vendors/onboarding",
     *  summary="Onboarding",
     *  description="Vendor Onboardings",
     *  operationId="VendorOnboardings",
     *  tags={"VendorOnboardings"},
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *      @OA\Property(
     *          property="data",
     *          type="array",
     *          @OA\Items(
     *              @OA\Property(property="id", type="integer", example=""),
     *              @OA\Property(property="title", type="string", example=""),
     *              @OA\Property(property="description", type="string", example=""),
     *              @OA\Property(property="image", type="string", example=""),
     *          ),
     *      ),
     *    ),
     *  )
     * )
     */
    public function index(Request $request)
    {
        return OnboardingResource::collection(Onboarding::where('type', 'vendor')->get());
    }
}
