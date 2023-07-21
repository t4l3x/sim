<?php

namespace Database\Seeders;

use App\Models\ScoringRule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScoringRulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        ScoringRule::create([
            'win_points' => 3,
            'draw_points' => 1,
            'loss_points' => 0,
        ]);
    }
}
