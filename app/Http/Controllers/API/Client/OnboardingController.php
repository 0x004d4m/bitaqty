<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\OnboardingResource;
use App\Models\Onboarding;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="ClientOnboardings",
 *     description="API Endpoints of Client Onboardings"
 * )
 */
class OnboardingController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/clients/onboarding",
     *  summary="Onboarding",
     *  description="Client Onboardings",
     *  operationId="ClientOnboardings",
     *  tags={"ClientOnboardings"},
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
        return OnboardingResource::collection(Onboarding::where('type', 'client')->get());
    }
}
