<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Services\MatchService;
use App\Http\Helpers\ApiHelpers;
use App\Http\Requests\WeekRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function __construct(
        protected MatchService $matchService
    )
    {
    }

    public function show(WeekRequest $request): JsonResponse
    {
        $week = $request->validated()['week'];

        $matches = $this->matchService->getMatchesForWeek($week);

        // Prepare the data to be returned as JSON

        return ApiHelpers::successResponse('OK', ['matches' => $matches]);

    }

    public function playWeek(Request $request, int $week): JsonResponse
    {
        try {
            $this->matchService->playWeek(1, $week);
            return ApiHelpers::successResponse('Week ' . $week . ' played successfully');
        } catch (\Exception $e) {
            return ApiHelpers::errorResponse($e->getMessage(), 500);
        }
    }

    public function playAllWeek(Request $request): JsonResponse
    {
        try {
            $this->matchService->playAll(1);
            return ApiHelpers::successResponse('All matches played successfully');
        } catch (\Exception $e) {
            return ApiHelpers::errorResponse($e->getMessage(), 500);
        }
    }
}
