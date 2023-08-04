<?php
declare(strict_types=1);

namespace Domain\Services;

use App\Domain\Repositories\IAttributesRepository;
use App\Domain\Services\TeamsService;
use PHPUnit\Framework\TestCase;
class TeamServiceTest extends TestCase
{
    public function testTeamStrengthCalculator(): void
    {
        // Mock dependencies
        $attributesRepositoryMock = $this->createMock(IAttributesRepository::class);

        // Sample team ID
        $teamId = 1;

        // Sample attributes for the team
        $attributes = [
            ['attribute_name' => 'attack', 'attribute_value' => 80],
            ['attribute_name' => 'defense', 'attribute_value' => 70],
            // Add more attributes if needed
        ];

        // Set up expectations for the mock dependencies
        $attributesRepositoryMock->expects($this->once())
            ->method('findByEntity')
            ->with('Team', $teamId)
            ->willReturn($attributes);

        // Create an instance of TeamsService with the mocked dependencies
        $teamsService = new TeamsService($attributesRepositoryMock);

        // Calculate the team strength
        $teamStrength = $teamsService->teamStrengthCalculator($teamId);

        // Perform assertions
        // The expected strength for the provided attributes: (80 + 70) / 2 = 75
        $this->assertEquals(75, $teamStrength);
    }

    public function testGetAttributesForTeam()
    {
        // Mock dependencies
        $attributesRepositoryMock = $this->createMock(IAttributesRepository::class);

        // Sample team ID
        $teamId = 1;

        // Sample attributes for the team
        $attributes = [
            ['attribute_name' => 'attack', 'attribute_value' => 80],
            ['attribute_name' => 'defense', 'attribute_value' => 70],
            // Add more attributes if needed
        ];

        // Set up expectations for the mock dependencies
        $attributesRepositoryMock->expects($this->once())
            ->method('findByEntity')
            ->with('Team', $teamId)
            ->willReturn($attributes);

        // Create an instance of TeamsService with the mocked dependencies
        $teamsService = new TeamsService($attributesRepositoryMock);

        // Get the attributes for the team
        $result = $teamsService->getAttributesForTeam($teamId);

        // Perform assertions
        $expectedResult = [
            'attack' => 80,
            'defense' => 70,
            // Add more attributes if needed
        ];
        $this->assertEquals($expectedResult, $result);
    }


}
