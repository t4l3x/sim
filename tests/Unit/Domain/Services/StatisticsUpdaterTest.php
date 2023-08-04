<?php
declare(strict_types=1);

namespace Domain\Services;

use App\Domain\Repositories\IStatisticsRepository;
use App\Domain\Services\StatisticsUpdater;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;


class StatisticsUpdaterTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testUpdateWithIncrement()
    {
        $mockRepository = $this->createMock(IStatisticsRepository::class);
        $updater = new StatisticsUpdater($mockRepository);

        $leagueTeamsId = 1;
        $goalsFor = 2;
        $goalsAgainst = 1;
        $increment = true;

        // Set up expectations for the repository
        $mockRepository->expects($this->once())
            ->method('updateStatistics')
            ->with(
                $this->equalTo($leagueTeamsId),
                $this->equalTo([
                    'played' => 1,
                    'won' => 1,
                    'draw' => 0,
                    'lost' => 0,
                    'goals_for' => 2,
                    'goals_against' => 1,
                    'goal_difference' => 1,
                    'points' => 3,
                ])
            );

        $updater->update($leagueTeamsId, $goalsFor, $goalsAgainst, $increment);
    }

    /**
     * @throws Exception
     */
    public function testUpdateWithDecrement()
    {
        // Arrange
        $leagueTeamsId = 1;
        $goalsFor = 1;
        $goalsAgainst = 2;
        $statisticsRepository = $this->createMock(IStatisticsRepository::class);
        $statisticsUpdater = new StatisticsUpdater($statisticsRepository);

        // Expectation: The updateStatistics method should be called with correct parameters
        $statisticsRepository
            ->expects($this->once())
            ->method('updateStatistics')
            ->with($leagueTeamsId, [
                'played' => -1,
                'won' => 0,
                'draw' => 0,
                'lost' => -1, // Change the value back to -1
                'goals_for' => -1,
                'goals_against' => -2,
                'goal_difference' => 1,
                'points' => 0,
            ]);

        // Act
        $statisticsUpdater->update($leagueTeamsId, $goalsFor, $goalsAgainst, false); // Change the fourth parameter back to false

        // Assert: We don't need to add any specific assertion, as the expectations will check the method call.
    }


    /**
     * @throws Exception
     */
    public function testUpdateWithEqualGoals()
    {
        $mockRepository = $this->createMock(IStatisticsRepository::class);
        $updater = new StatisticsUpdater($mockRepository);

        $leagueTeamsId = 1;
        $goalsFor = 2;
        $goalsAgainst = 2;
        $increment = true;

        // Set up expectations for the repository
        $mockRepository->expects($this->once())
            ->method('updateStatistics')
            ->with(
                $this->equalTo($leagueTeamsId),
                $this->equalTo([
                    'played' => 1,
                    'won' => 0,
                    'draw' => 1,
                    'lost' => 0,
                    'goals_for' => 2,
                    'goals_against' => 2,
                    'goal_difference' => 0,
                    'points' => 1,
                ])
            );

        $updater->update($leagueTeamsId, $goalsFor, $goalsAgainst, $increment);
    }

    /**
     * @throws Exception
     */
    public function testUpdateWithZeroGoals()
    {
        $mockRepository = $this->createMock(IStatisticsRepository::class);
        $updater = new StatisticsUpdater($mockRepository);

        $leagueTeamsId = 1;
        $goalsFor = 0;
        $goalsAgainst = 0;
        $increment = true;

        // Set up expectations for the repository
        $mockRepository->expects($this->once())
            ->method('updateStatistics')
            ->with(
                $this->equalTo($leagueTeamsId),
                $this->equalTo([
                    'played' => 1,
                    'won' => 0,
                    'draw' => 1,
                    'lost' => 0,
                    'goals_for' => 0,
                    'goals_against' => 0,
                    'goal_difference' => 0,
                    'points' => 1,
                ])
            );

        $updater->update($leagueTeamsId, $goalsFor, $goalsAgainst, $increment);
    }

    // Add more test cases for other scenarios as needed
}
