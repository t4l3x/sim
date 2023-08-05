<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Services\MatchService;
use App\Http\Helpers\ApiHelpers;
use App\Http\Requests\MatchRequest;
use Illuminate\Http\JsonResponse;


class MatchController extends Controller
{
    public function __construct(
        protected MatchService $matchService
    )
    {
    }

    public function show(MatchRequest $request): JsonResponse
    {
        $week = $request->validated()['week'];
//        $league = $request->validated()['league'];

        $matches = $this->matchService->getMatchesForWeek($week,1);

        return ApiHelpers::successResponse('OK', ['matches' => $matches]);

    }

    public function allMatches(MatchRequest $request): JsonResponse
    {
        $league = $request->validated()['league'];

        $matches = $this->matchService->getAllMatches(1);

        return ApiHelpers::successResponse('OK', ['matches' => $matches]);
    }
    public function playWeek(MatchRequest $request): JsonResponse
    {
        $week = $request->validated()['week'];
        try {
            $this->matchService->playWeek(1, $week);
            return ApiHelpers::successResponse('Week ' . $week . ' played successfully');
        } catch (\Exception $e) {
            return ApiHelpers::errorResponse($e->getMessage(), 500);
        }
    }

    public function playAllWeek(MatchRequest $request): JsonResponse
    {
        try {
            $this->matchService->playAll(1);
            return ApiHelpers::successResponse('All matches played successfully');
        } catch (\Exception $e) {
            return ApiHelpers::errorResponse($e->getMessage(), 500);
        }
    }
}
