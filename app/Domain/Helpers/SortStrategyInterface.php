<?php
declare(strict_types=1);

namespace App\Domain\Helpers;

interface SortStrategyInterface
{
    public function sort(array $data): array;

}
