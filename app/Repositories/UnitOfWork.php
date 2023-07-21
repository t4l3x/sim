<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Repositories\IUnitOfWork;
use Illuminate\Support\Facades\DB;

class UnitOfWork implements IUnitOfWork
{
    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollback(): void
    {
        DB::rollBack();
    }
}
