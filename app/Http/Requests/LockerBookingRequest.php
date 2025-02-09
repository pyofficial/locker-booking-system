<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LockerBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->route()->getName() == 'lockers.check-availability') {
            return [
                'date' => [
                    'required',
                    'date_format:Y-m-d',
                    'after_or_equal:' . Carbon::today()->toDateString(),
                    'before_or_equal:' . Carbon::today()->addDays(15)->toDateString()
                ],
                'locker_station_id' => [
                    'nullable',
                    'integer',
                    Rule::exists('locker_stations', 'id')->whereNull('deleted_at')
                ],
                'locker_id' => [
                    'nullable',
                    'integer',
                    Rule::exists('lockers', 'id')->whereNull('deleted_at')
                ],
                'sizes' => ['nullable', 'string', 'in:S,M,L']
            ];
        }

        if ($this->route()->getName() == 'lockers.book-locker') {
            return [
                'locker_station_id' => [
                    'required',
                    'integer',
                    Rule::exists('locker_stations', 'id')->whereNull('deleted_at')
                ],
                'locker_id' => [
                    'required',
                    'integer',
                    Rule::exists('lockers', 'id')->whereNull('deleted_at')
                ],
                'time_slot_id' => [
                    'required',
                    'integer',
                    Rule::exists('time_slots', 'id')->whereNull('deleted_at')
                ],
                'user_id' => [
                    'required',
                    'integer',
                    Rule::exists('users', 'id')->whereNull('deleted_at')
                ],
                'date' => [
                    'required',
                    'date_format:Y-m-d',
                    'after_or_equal:' . Carbon::today()->toDateString(),
                    'before_or_equal:' . Carbon::today()->addDays(15)->toDateString()
                ]
            ];
        }

        if ($this->route()->getName() == 'lockers.cancel-booking') {
            return [
                'booking_id' => [
                    'required',
                    'integer',
                    Rule::exists('booking_schedules', 'id')->whereNull('deleted_at'),
                ],
            ];
        }
        return [];
    }

    public function attributes()
    {
        return [
            'date' => 'Date',
            'locker_station_id' => 'Locker Station',
            'locker_id' => 'Locker',
            'sizes' => 'Locker Size',
            'booking_id' => 'Booking',
            'user_id' => 'User'
        ];
    }
}
