<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LockerBookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'locker_station_id' => $this->locker_station_id,
            'locker_station_name' => $this->lockerStation->name ?? null,
            'locker_id' => $this->locker_id,
            'locker_size' => $this->locker->size ?? null,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name ?? null,
            'time_slot_id' => $this->time_slot_id,
            'booking_start_time' => $this->timeSlot->start_time ?? null,
            'booking_end_time' => $this->timeSlot->end_time ?? null,
        ];
    }
}
