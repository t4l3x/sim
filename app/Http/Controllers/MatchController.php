<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Services\MatchService;
use App\Http\Helpers\ApiHelpers;
use App\Http\Requests\MatchUpdateRequest;
use App\Http\Requests\WeekRequest;
use App\Models\Leagues;
use App\Models\Matches;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class MatchController extends Controller
{
    public function __construct(
        protected MatchService $matchService
    )
    {
    }

    public function show(Leagues $league, WeekRequest $request): JsonResponse
    {
        $week = $request->validated()['week'];

        $matches = $this->matchService->getMatchesForWeek($week, $league->id);

        return ApiHelpers::successResponse('OK', ['matches' => $matches]);

    }

    public function playWeek(Leagues $league, WeekRequest $request): JsonResponse
    {
        $week = $request->validated()['week'];
        try {
            $this->matchService->playWeek($league->id, $week);
            return ApiHelpers::successResponse('Week ' . $week . ' played successfully');
        } catch (\Exception $e) {
            return ApiHelpers::errorResponse($e->getMessage(), 500);
        }
    }

    public function playAllWeek(Leagues $league): JsonResponse
    {
        try {
            $this->matchService->playAll($league->id);
            return ApiHelpers::successResponse('All matches played successfully');
        } catch (\Exception $e) {
            return ApiHelpers::errorResponse($e->getMessage(), 500);
        }
    }

    public function totalWeeks(Leagues $league): JsonResponse
    {
        try {
            $totalWeeks = $this->matchService->totalWeeks($league->id);
            return ApiHelpers::successResponse('OK', ['total' => $totalWeeks]);
        } catch (\Exception $e) {
            return ApiHelpers::errorResponse($e->getMessage(), 500);
        }
    }

    public function updateResult( Matches $matchId, MatchUpdateRequest $request): JsonResponse
    {

        try {
            $homeGoals = $request->validated()['home_goals'];
            $awayGoals =  $request->validated()['away_goals'];
            $this->matchService->manuallyUpdateResult($matchId->id, $homeGoals, $awayGoals);
            return ApiHelpers::successResponse('All matches played successfully');
        } catch (\Exception $e) {
            return ApiHelpers::errorResponse($e->getMessage(), 500);
        }
    }
}
