<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Services\MatchService;
use App\Domain\Services\StandingsService;
use App\Models\Matches;
use App\Domain\Services\LeagueGeneratorService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class SimulationController extends Controller
{
    public function __construct(
        protected LeagueGeneratorService $leagueService,
        protected StandingsService       $standingsService,
        protected MatchService           $matchService
    )
    {
    }

    public function simulate(): View
    {
        $this->leagueService->generateMatches(1);
        return view('standings');
    }

    public function resetLeague(): JsonResponse
    {
        $this->leagueService->resetMatches(1); // Replace 1 with the appropriate league ID
        // You may also want to reset any other related data for the league

        return response()->json(['message' => 'League reset successfully']);

    }

    public function genereteMatchesForLeague(LeagueGeneratorService $leagueService)
    {
        $this->leagueService->generateMatches(1);
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

