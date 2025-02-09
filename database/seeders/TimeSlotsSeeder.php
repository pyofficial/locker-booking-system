<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use Illuminate\Database\Seeder;

class TimeSlotsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timeSlots = [
            ['start_time' => '01:00:00', 'end_time' => '03:00:00'],
            ['start_time' => '05:00:00', 'end_time' => '07:00:00'],
            ['start_time' => '09:00:00', 'end_time' => '11:00:00'],
        ];

        foreach ($timeSlots as $slot) {
            TimeSlot::create($slot);
        }
    }
}
