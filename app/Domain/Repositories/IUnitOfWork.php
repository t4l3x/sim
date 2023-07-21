<?php
declare(strict_types=1);

namespace App\Domain\Repositories;

interface IUnitOfWork
{
    public function beginTransaction(): void;
    public function commit(): void;
    public function rollback(): void;
}
