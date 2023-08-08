<?php

namespace App\Providers;

use App\Domain\Helpers\PredictionsSorter;
use App\Domain\Helpers\SortStrategyInterface;
use App\Domain\Helpers\StandingsSorter;
use App\Domain\Repositories\IAttributesRepository;
use App\Domain\Repositories\ILeagueTeamsRepository;
use App\Domain\Repositories\IMatchRepository;
use App\Domain\Repositories\IStatisticsRepository;
use App\Domain\Repositories\ITeamsRepository;
use App\Domain\Repositories\IUnitOfWork;
use App\Domain\Services\PredictionService;
use App\Domain\Services\StandingsService;
use App\Repositories\AttributesRepository;
use App\Repositories\LeagueTeamsRepository;
use App\Repositories\MatchRepository;
use App\Repositories\StatisticsRepository;
use App\Repositories\TeamsRepository;
use App\Repositories\UnitOfWork;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ITeamsRepository::class,
            TeamsRepository::class
        );
        $this->app->bind(
            IMatchRepository::class,
            MatchRepository::class
        );
        $this->app->bind(
            IStatisticsRepository::class,
            StatisticsRepository::class
        );
        $this->app->bind(
            IAttributesRepository::class,
            AttributesRepository::class
        );
        $this->app->bind(
            ILeagueTeamsRepository::class,
            LeagueTeamsRepository::class
        );
        $this->app->bind(
            IUnitOfWork::class,
            UnitOfWork::class
        );

        $this->app->when(PredictionService::class)
            ->needs(SortStrategyInterface::class)
            ->give(PredictionsSorter::class);

        $this->app->when(StandingsService::class)
            ->needs(SortStrategyInterface::class)
            ->give(StandingsSorter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
