<?php

namespace Database\Seeders;

use App\Models\Leagues;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaguesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Leagues::create([
            'name' => 'Premier League',
        ]);
    }
}
