<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Services\StandingsService;
use App\Http\Helpers\ApiHelpers;
use App\Models\Leagues;
use Illuminate\Http\JsonResponse;


class StandingsController extends Controller
{
    public function __construct(
        protected StandingsService $standingsService,
    )
    {
    }

    public function show(Leagues $league): JsonResponse
    {

        // Retrieve the standings, matches, and predictions for the given week
        $standings = $this->standingsService->calculateLeagueStandings($league->id);

        // Prepare the data to be returned as JSON

        return ApiHelpers::successResponse('OK', ['standings' => $standings]);

    }

}
