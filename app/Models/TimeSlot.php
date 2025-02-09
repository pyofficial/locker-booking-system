<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeSlot extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['start_time', 'end_time'];

    public function bookingSchedules()
    {
        return $this->hasMany(BookingSchedule::class);
    }
}
