<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Repositories\ITeamsRepository;
use App\Models\Teams;

class TeamsRepository implements ITeamsRepository
{
    public function __construct(protected Teams $model)
    {
    }

    public function findById(int $id): array
    {
        $team = $this->model->find($id);
        return $team ? $team->toArray() : [];
    }

    public function findAll(): array
    {
        return $this->model->all()->toArray();
    }

    public function save($data): bool
    {
        $team = $data['id'] ? $this->model->find($data['id']) : new $this->model;
        $team->fill($data);
        return $team->save();
    }

    public function delete(int $id): bool
    {
        $team = $this->model->find($id);
        return $team ? $team->delete() : false;
    }

    public function getTeamsByLeagueId(mixed $id)
    {
        // TODO: Implement getTeamsByLeagueId() method.
    }
}
