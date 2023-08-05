<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Services\MatchService;
use App\Domain\Services\StandingsService;
use App\Http\Helpers\ApiHelpers;
use App\Domain\Services\LeagueGeneratorService;
use App\Http\Requests\MatchRequest;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;



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
        return view('standings');
    }

    /**
     * @throws Exception
     */
    public function resetLeague(): JsonResponse
    {
        $this->leagueService->resetMatches(1); // Replace 1 with the appropriate league ID
        // You may also want to reset any other related data for the league

        return ApiHelpers::successResponse('League reset successfully');

    }

    public function League(MatchRequest $league): JsonResponse
    {

    }

}

