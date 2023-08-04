<?php
declare(strict_types=1);

namespace Domain\Services;

use App\Domain\Repositories\IAttributesRepository;
use App\Domain\Repositories\ILeagueTeamsRepository;
use App\Domain\Repositories\IStatisticsRepository;
use App\Domain\Services\StandingsService;
use PHPUnit\Framework\TestCase;

class StandingsServiceTest extends TestCase
{
    public function testCalculateLeagueStandings()
    {
        // Mock dependencies
        $leagueTeamsRepositoryMock = $this->createMock(ILeagueTeamsRepository::class);
        $statisticsRepositoryMock = $this->createMock(IStatisticsRepository::class);
        $attributesRepositoryMock = $this->createMock(IAttributesRepository::class);

        // Sample league ID
        $leagueId = 1;

        // Sample teams data
        $teams = [
            ['id' => 1, 'team' => ['id' => 101, 'name' => 'Team A']],
            ['id' => 2, 'team' => ['id' => 102, 'name' => 'Team B']],
            // Add more teams if needed
        ];

        // Sample statistics data
        $statistics = [
            ['played' => 10, 'won' => 7, 'lost' => 2, 'draw' => 1, 'points' => 22, 'goal_difference' => 10],
            ['played' => 9, 'won' => 5, 'lost' => 3, 'draw' => 1, 'points' => 16, 'goal_difference' => 5],
            // Add more statistics data if needed
        ];

        // Set up expectations for the mock dependencies
        $leagueTeamsRepositoryMock->expects($this->once())
            ->method('getTeamsByLeagueId')
            ->with($leagueId)
            ->willReturn($teams);

        $statisticsRepositoryMock->method('findByLeagueTeamsId')
            ->willReturnMap([
                [1, $statistics[0]], // Team with ID 1 will return $statistics[0]
                [2, $statistics[1]], // Team with ID 2 will return $statistics[1]
                // Add more entries for other teams if needed
            ]);

        // Create an instance of StandingsService with the mocked dependencies
        $standingsService = new StandingsService(
            $leagueTeamsRepositoryMock,
            $statisticsRepositoryMock,
            $attributesRepositoryMock
        );

        // Calculate the league standings
        $result = $standingsService->calculateLeagueStandings($leagueId);

        // Define the expected standings based on the provided statistics data
        $expectedStandings = [
            [
                'team_id' => 101,
                'team_name' => 'Team A',
                'played' => 10,
                'won' => 7,
                'lost' => 2,
                'draw' => 1,
                'points' => 22,
                'goal_difference' => 10,
            ],
            [
                'team_id' => 102,
                'team_name' => 'Team B',
                'played' => 9,
                'won' => 5,
                'lost' => 3,
                'draw' => 1,
                'points' => 16,
                'goal_difference' => 5,
            ],
            // Add more expected standings if needed
        ];

        // Perform assertions
        $this->assertEquals($expectedStandings, $result);
    }

    public function testGetAttributesForTeam()
    {
        // Mock IAttributesRepository
        $attributesRepositoryMock = $this->createMock(IAttributesRepository::class);

        // Sample team ID
        $teamId = 101;

        // Sample attributes for the team
        $attributes = [
            ['attribute_name' => 'attack', 'attribute_value' => 80],
            ['attribute_name' => 'defense', 'attribute_value' => 70],
            // Add more attributes if needed
        ];

        // Set up expectations for the mock IAttributesRepository
        $attributesRepositoryMock->expects($this->once())
            ->method('findByEntity')
            ->with('Team', $teamId)
            ->willReturn($attributes);

        // Create an instance of StandingsService with the mocked IAttributesRepository
        $standingsService = new StandingsService(
            $this->createMock(ILeagueTeamsRepository::class),
            $this->createMock(IStatisticsRepository::class),
            $attributesRepositoryMock
        );

        // Get the attributes for the team
        $result = $standingsService->getAttributesForTeam($teamId);

        // Perform assertions
        $expectedResult = [
            'attack' => 80,
            'defense' => 70,
            // Add more attributes if needed
        ];
        $this->assertEquals($expectedResult, $result);
    }

    // Add more test cases to cover different scenarios if needed
}
