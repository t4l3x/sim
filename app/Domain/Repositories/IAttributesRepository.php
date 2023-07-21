<?php
declare(strict_types=1);

namespace App\Domain\Repositories;

interface IAttributesRepository extends IRepository
{
    public function getTeamAttribute(int $teamId, string $attributeName): mixed;

    public function findByEntity($entityType, $entityId): array;


    public function update(int $id, array $data): bool;

}
