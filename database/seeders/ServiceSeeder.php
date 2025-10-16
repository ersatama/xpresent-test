<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workingDays = json_encode([1, 2, 3, 4, 5, 6]); // пн–сб

        Service::insert([
            ['name' => 'Поездка на квадроцикле 30 минут', 'duration_minutes' => 30, 'working_days' => $workingDays],
            ['name' => 'Поездка на квадроцикле 60 минут', 'duration_minutes' => 60, 'working_days' => $workingDays],
            ['name' => 'Тур на эндуро 60 минут', 'duration_minutes' => 60, 'working_days' => $workingDays],
            ['name' => 'Тур на эндуро 120 минут', 'duration_minutes' => 120, 'working_days' => $workingDays],
        ]);
    }
}
