<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Repositories\IAttributesRepository;
use App\Models\Attribute;

class AttributesRepository implements IAttributesRepository
{
    public function __construct(protected Attribute $attribute)
    {
    }

    public function getTeamAttribute(int $teamId, string $attributeName): mixed
    {
        // TODO: Implement getTeamAttribute() method.
    }

    public function findByEntity($entityType, $entityId): array
    {
        $attributes = $this->attribute->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->get();
        return $attributes->toArray();
    }

    public function update(int $id, array $data): bool
    {
        $attribute = $this->attribute->find($id);
        if ($attribute) {
            $attribute->entity_type = $data['entity_type'] ?? $attribute->entity_type;
            $attribute->entity_id = $data['entity_id'] ?? $attribute->entity_id;
            $attribute->attribute_name = $data['attribute_name'] ?? $attribute->attribute_name;
            $attribute->attribute_value = $data['attribute_value'] ?? $attribute->attribute_value;

            return $attribute->save();
        }
        return false;
    }

    public function findById(int $id): array
    {
        $attribute = $this->attribute->find($id);
        return $attribute ? $attribute->toArray() : [];
    }

    public function findAll(): array
    {
        return $this->attribute->all()->toArray();
    }

    public function save($data): bool
    {
        $attribute = new $this->attribute;
        $attribute->entity_type = $data['entity_type'];
        $attribute->entity_id = $data['entity_id'];
        $attribute->attribute_name = $data['attribute_name'];
        $attribute->attribute_value = $data['attribute_value'];

        return $attribute->save();
    }

    public function delete(int $id): bool
    {
        $attribute = $this->attribute->find($id);
        return $attribute ? $attribute->delete() : false;
    }
}
