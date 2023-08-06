<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Services\MatchService;
use App\Domain\Services\StandingsService;
use App\Http\Helpers\ApiHelpers;
use App\Domain\Services\LeagueGeneratorService;
use App\Http\Requests\MatchUpdateRequest;
use App\Models\Leagues;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;



class LeagueController extends Controller
{
    public function __construct(
        protected LeagueGeneratorService $leagueService,

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
    public function resetLeague(Leagues $league): JsonResponse
    {
        $this->leagueService->resetMatches($league->id);

        return ApiHelpers::successResponse('League reset successfully');

    }

    public function leagueInfo(Leagues $league): JsonResponse
    {

        return ApiHelpers::successResponse('');
    }

}

