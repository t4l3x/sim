<?php
declare(strict_types=1);

namespace App\Domain\Repositories;

interface IRepository
{
    public function findById(int $id): array;

    public function findAll(): array;

    public function save($data): bool;

    public function delete(int $id): bool;


}
