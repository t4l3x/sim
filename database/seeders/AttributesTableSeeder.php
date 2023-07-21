<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\CommonMark\Extension\Attributes\Node\Attributes;

class AttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i = 1; $i <= 4; $i++) {
            Attribute::create([
                'entity_type' => 'Team', // Assuming we're attaching the attribute to a team
                'entity_id' => $i, // Assuming we're attaching the attribute to the team with ID = $i
                'attribute_name' => 'historical_performance',
                'attribute_value' => rand(70, 100), // Random historical performance value
            ]);

            Attribute::create([
                'entity_type' => 'Team',
                'entity_id' => $i,
                'attribute_name' => 'player_health',
                'attribute_value' => rand(80, 100), // Random player health value
            ]);
            Attribute::create([
                'entity_type' => 'Team',
                'entity_id' => $i,
                'attribute_name' => 'strength',
                'attribute_value' => rand(80, 100), // Random streng  value
            ]);
        }
    }
}
