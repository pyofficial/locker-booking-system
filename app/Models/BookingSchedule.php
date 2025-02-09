<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['locker_station_id', 'locker_id', 'user_id', 'time_slot_id', 'booking_date', 'status'];

    public const STATUS_ACTIVE = 'Active';
    public const STATUS_INACTIVE = 'Inactive';
    public const STATUS_CANCELLED = 'Cancelled';
    
    public static function statuses()
    {
        return [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_CANCELLED];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lockerStation()
    {
        return $this->belongsTo(LockerStation::class);
    }

    public function locker()
    {
        return $this->belongsTo(Locker::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }
}
