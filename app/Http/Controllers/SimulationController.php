<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Matches;
use App\Domain\Services\LeagueGeneratorService;
use Illuminate\Contracts\View\View;


class SimulationController extends Controller
{
    public function __construct( protected LeagueGeneratorService $leagueService)
    {

    }

    public function simulate(): View
    {
        $this->leagueService->generateMatches(1);
        $matches = Matches::where('leagues_id', 1)->get(); // Assuming league_id 1 is the desired league
        return view('standings', compact('matches'));
    }

}

