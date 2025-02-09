<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locker extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['locker_station_id', 'locker_size'];

    public const SIZE_SMALL = 'S';
    public const SIZE_MEDIUM = 'M';
    public const SIZE_LARGE = 'L';

    public static function sizes()
    {
        return [self::SIZE_SMALL, self::SIZE_MEDIUM, self::SIZE_LARGE];
    }

    public function lockerStation()
    {
        return $this->belongsTo(LockerStation::class);
    }

    public function bookingSchedules()
    {
        return $this->hasMany(BookingSchedule::class);
    }
}
