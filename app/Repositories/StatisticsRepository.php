<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Repositories\IStatisticsRepository;
use App\Models\Statistics;

class StatisticsRepository implements IStatisticsRepository
{
    public function __construct(protected Statistics $model)
    {
    }

    public function findById(int $id): array
    {
        $statistic = $this->model->find($id);
        return $statistic ? $statistic->toArray() : [];
    }

    public function findAll(): array
    {
        return $this->model->all()->toArray();
    }

    public function save($data): bool
    {
        $statistic = new $this->model();
        $statistic->fill($data);
        return $statistic->save();
    }

    public function delete(int $id): bool
    {
        $statistic = $this->model->find($id);
        return $statistic ? $statistic->delete() : false;
    }

    public function updateStatistics(int $leagueTeamsId, array $stats): void
    {
        foreach ($stats as $name => $value) {
            // Attempt to find existing statistic record
            $statistic = $this->model->firstOrNew(['league_teams_id' => $leagueTeamsId, 'statistic_name' => $name]);
            $statistic->statistic_value = $statistic->statistic_value ? $statistic->statistic_value + $value : $value;

            // Save the statistic record
            $statistic->save();
        }
    }

    public function removeByLeagueTeamsId(int $leagueTeamsId): void
    {
        $this->model->where('league_teams_id', $leagueTeamsId)->delete();
    }

    public function findByLeagueTeamsId(int $id)
    {
        return $this->model->where('league_teams_id', $id)
            ->pluck('statistic_value', 'statistic_name')
            ->toArray();
    }
}
