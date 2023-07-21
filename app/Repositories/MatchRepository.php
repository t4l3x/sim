<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Repositories\IMatchRepository;
use App\Models\Matches;

class MatchRepository implements IMatchRepository
{
    public function __construct(protected Matches $matches)
    {
    }

    public function matchesExistForLeague(int $leagueId): bool
    {
        return $this->matches->where('leagues_id', $leagueId)->exists();
    }

    public function getMatchesForWeek($week): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->matches->with(['homeTeam' => fn ($query) => $query->select('id', 'name'),
            'awayTeam' => fn ($query) => $query->select('id', 'name')])
            ->where('week', $week)
            ->get(['id','home_team_id', 'away_team_id', 'home_team_goals', 'away_team_goals','week'])->toArray();
    }

    public function updateMatchResult(int $matchId, array $result): void
    {
        $match = $this->matches->findOrFail($matchId);
        $match->update([
            'played' => $result['played'],
            'home_team_goals' => $result['homeGoals'],
            'away_team_goals' => $result['awayGoals'],
        ]);
    }

    public function findByWeek(int $leagueId, int $weekNumber): array
    {
        return $this->matches->where('leagues_id', $leagueId)
            ->where('week', $weekNumber)
            ->get()
            ->toArray();
    }

    public function getTotalWeeks(int $leagueId): int
    {
        return $this->matches->where('leagues_id', $leagueId)
            ->max('week');
    }

    public function findById(int $id): array
    {
        $match = $this->matches->findOrFail($id);
        return $match->toArray();
    }

    public function findAll(): array
    {
        return $this->matches->all()->toArray();
    }

    public function save($data): bool
    {
        $match = new $this->matches($data);
        return $match->save();
    }

    public function delete(int $id): bool
    {
        $match = $this->matches->findOrFail($id);
        return $match->delete();
    }
}
