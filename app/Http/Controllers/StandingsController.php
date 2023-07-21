<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Services\MatchService;
use App\Domain\Services\StandingsService;
use App\Domain\Services\PredictionService;
use App\Http\Requests\WeekRequest;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class StandingsController extends Controller
{
    public function __construct(
        protected PredictionService $predictionService,
        protected StandingsService  $standingsService,
        protected MatchService      $matchService
    )
    {
    }

    public function show(WeekRequest $request): JsonResponse
    {
        $week = $request->validated()['week'];


        // Retrieve the standings, matches, and predictions for the given week
        $standings = $this->standingsService->calculateLeagueStandings(1);

        $matches = $this->matchService->getMatchesForWeek($week);

        $predictions = $this->predictionService->getPredictions($week);

        // Prepare the data to be returned as JSON
        $data = [
            'standings' => $standings,
            'matches' => $matches,
            'predictions' => $predictions
        ];

        return response()->json($data);
    }

    public function playWeek(Request $request, int $week): JsonResponse
    {
        try {
            $this->matchService->playWeek(1, $week);
            return response()->json(['status' => 'success', 'message' => 'Week ' . $week . ' played successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function playAllWeek(Request $request): JsonResponse
    {
        try {
            $this->matchService->playAll(1);
            return response()->json(['status' => 'success', 'message' => 'All played successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

}
