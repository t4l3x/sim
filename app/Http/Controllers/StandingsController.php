<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Services\StandingsService;
use App\Http\Helpers\ApiHelpers;
use App\Http\Requests\WeekRequest;
use Illuminate\Http\JsonResponse;


class StandingsController extends Controller
{
    public function __construct(
        protected StandingsService $standingsService,
    )
    {
    }

    public function show(WeekRequest $request): JsonResponse
    {
        $week = $request->validated()['week'];

        // Retrieve the standings, matches, and predictions for the given week
        $standings = $this->standingsService->calculateLeagueStandings(1);

        // Prepare the data to be returned as JSON

        return ApiHelpers::successResponse('OK', ['standings' => $standings]);

    }

}
