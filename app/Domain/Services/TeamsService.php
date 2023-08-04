<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Repositories\IAttributesRepository;

class TeamsService
{

    public function __construct(
        private readonly IAttributesRepository $attributesRepository
    )
    {
    }

    public function teamStrengthCalculator($teamId): float|int
    {
        $attr = $this->getAttributesForTeam($teamId);

        $strength = 0;

        foreach ($attr as $key => $value) {
            $strength += $value;
        }

        return $strength / 2;
    }

    public function getAttributesForTeam($teamId): array
    {
        $attributesArray = $this->attributesRepository->findByEntity('Team', $teamId);
        return $this->transformAttributes($attributesArray);
    }

    private function transformAttributes(array $attributes): array
    {
        $transformed = [];

        foreach ($attributes as $attribute) {
            $transformed[$attribute['attribute_name']] = $attribute['attribute_value'];
        }

        return $transformed;
    }

}
