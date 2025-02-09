<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LockerStation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'location'];

    public function lockers()
    {
        return $this->hasMany(Locker::class);
    }

    public function bookingSchedules()
    {
        return $this->hasMany(BookingSchedule::class);
    }
}
