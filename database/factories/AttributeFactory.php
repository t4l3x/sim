<?php

namespace Database\Factories;

use App\Models\Leagues;
use App\Models\Teams;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attribute>
 */
class AttributeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $entityType = $this->faker->randomElement(['Teams', 'Leagues']);
        $entityId = $entityType === 'Teams' ? Teams::factory()->create()->id : Leagues::factory()->create()->id;

        $attributeName = $this->faker->randomElement(['Formation', 'Stadium', 'Coach', 'City']);
        $attributeValue = $this->generateAttributeValue($attributeName);

        return [
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'attribute_name' => $attributeName,
            'attribute_value' => $attributeValue
        ];
    }

    /**
     * Generate a value based on the attribute name.
     *
     * @param string $attributeName
     * @return mixed
     */
    private function generateAttributeValue(string $attributeName): mixed
    {
        switch ($attributeName) {
            case 'Formation':
                return $this->faker->randomElement(['4-4-2', '4-3-3', '3-5-2']);
            case 'Stadium':
                return $this->faker->randomElement(['Old Trafford', 'Camp Nou', 'Allianz Arena']);
            case 'Coach':
                return $this->faker->name;
            case 'City':
                return $this->faker->city;
            default:
                return $this->faker->word;
        }
    }
}
