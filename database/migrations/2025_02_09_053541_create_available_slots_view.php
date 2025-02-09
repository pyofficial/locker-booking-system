<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("DROP VIEW IF EXISTS available_slots");

        DB::statement("
            CREATE OR REPLACE VIEW available_slots AS
            WITH RECURSIVE date_series AS (
                SELECT CURDATE() AS booking_date
                UNION ALL
                SELECT DATE_ADD(booking_date, INTERVAL 1 DAY) 
                FROM date_series 
                WHERE booking_date < CURDATE() + INTERVAL 30 DAY
            )
            SELECT 
                date_series.booking_date,
                ls.id AS locker_station_id,
                ls.name AS station_name,
                ls.location,
                l.id AS locker_id,
                l.locker_size,
                ts.start_time,
                ts.end_time
            FROM date_series
            JOIN locker_stations ls ON 1=1 AND ls.deleted_at IS NULL
            JOIN lockers l ON ls.id = l.locker_station_id AND l.deleted_at IS NULL
            JOIN time_slots ts ON 1=1 AND ts.deleted_at IS NULL
            LEFT JOIN booking_schedules bs 
                ON l.id = bs.locker_id 
                AND ts.id = bs.time_slot_id 
                AND bs.booking_date = date_series.booking_date
                AND bs.status = 'Active'
            WHERE bs.id IS NULL  -- Ensuring only available slots are included
            ORDER BY date_series.booking_date, ls.id, l.id, ts.start_time;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
