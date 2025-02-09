<?php

namespace Database\Seeders;

use App\Models\Locker;
use App\Models\LockerStation;
use Illuminate\Database\Seeder;

class LockerStationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lockerSizes = ['S', 'S', 'M', 'M', 'L'];

        for ($i = 1; $i <= 5; $i++) {
            // Create Locker Station
            $lockerStation = LockerStation::create([
                'name' => "Locker Station $i",
                'location' => "Location $i",
            ]);

            // Create 5 Lockers for each station
            foreach ($lockerSizes as $size) {
                Locker::create([
                    'locker_station_id' => $lockerStation->id,
                    'locker_size' => $size,
                ]);
            }
        }
    }
}
