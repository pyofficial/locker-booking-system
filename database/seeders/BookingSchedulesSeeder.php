<?php

namespace Database\Seeders;

use App\Models\BookingSchedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSchedulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookings = [
            ['locker_station_id' => 1, 'locker_id' => 1, 'user_id' => 3, 'time_slot_id' => 2, 'booking_date' => '2025-02-09', 'status' => 'Active'],
            ['locker_station_id' => 2, 'locker_id' => 2, 'user_id' => 5, 'time_slot_id' => 3, 'booking_date' => '2025-02-12', 'status' => 'Active'],
            ['locker_station_id' => 3, 'locker_id' => 4, 'user_id' => 7, 'time_slot_id' => 1, 'booking_date' => '2025-02-10', 'status' => 'Active'],
            ['locker_station_id' => 5, 'locker_id' => 9, 'user_id' => 5, 'time_slot_id' => 2, 'booking_date' => '2025-02-13', 'status' => 'Active'],
            ['locker_station_id' => 3, 'locker_id' => 11, 'user_id' => 1, 'time_slot_id' => 1, 'booking_date' => '2025-02-09', 'status' => 'Active'],
            ['locker_station_id' => 4, 'locker_id' => 13, 'user_id' => 2, 'time_slot_id' => 3, 'booking_date' => '2025-02-10', 'status' => 'Active'],
            ['locker_station_id' => 4, 'locker_id' => 15, 'user_id' => 3, 'time_slot_id' => 2, 'booking_date' => '2025-02-13', 'status' => 'Active'],
            ['locker_station_id' => 5, 'locker_id' => 17, 'user_id' => 4, 'time_slot_id' => 3, 'booking_date' => '2025-02-09', 'status' => 'Active'],
            ['locker_station_id' => 5, 'locker_id' => 19, 'user_id' => 5, 'time_slot_id' => 1, 'booking_date' => '2025-02-14', 'status' => 'Active'],
        ];

        foreach ($bookings as $booking) {
            BookingSchedule::create($booking);
        }
    }
}
