<?php

namespace App\Repositories;

use App\Http\Resources\LockerBookingResource;
use App\Models\BookingSchedule;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class LockerBookingsRepository
{
    public function getAvailableSlots($request): JsonResponse
    {
        try {
            $startDate = $request->date ?? date('Y-m-d'); // Default to today
            $endDate = date('Y-m-d', strtotime("$startDate +6 days"));

            $query = DB::table('available_slots')
                ->whereBetween('booking_date', [$startDate, $endDate]);

            if (!is_null($request->locker_station_id)) {
                $query->where('locker_station_id', $request->locker_station_id);
            }

            if (!is_null($request->locker_id)) {
                $query->where('locker_id', $request->locker_id);
            }

            if (!is_null($request->sizes)) {
                $sizes = explode(",", $request->sizes);
                $query->whereIn('locker_size', $sizes);
            }

            $results = $query->orderBy('booking_date')
                ->orderBy('locker_station_id')
                ->orderBy('locker_id')
                ->orderBy('locker_size')
                ->get()->groupBy('booking_date');

            $data = $this->processAvailableSlots($results);

            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'Data fetched successfully.'
            ];
            return response()->json($response, 200);
        } catch (Exception $e) {
            $data = [
                'success' => false,
                'data' => [],
                'message' => 'Resource not found.'
            ];
            return response()->json($data, 500);
        }
    }

    private function processAvailableSlots($results)
    {
        return $results->map(function ($slotsByDate) {
            return $slotsByDate->groupBy(fn($slot) => "{$slot->locker_station_id}-{$slot->locker_id}-{$slot->locker_size}")
                ->map(function ($lockerSlots) {
                    return [
                        'locker_station_id' => $lockerSlots->first()->locker_station_id,
                        'station_name' => $lockerSlots->first()->station_name,
                        'location' => $lockerSlots->first()->location,
                        'locker_id' => $lockerSlots->first()->locker_id,
                        'locker_size' => $lockerSlots->first()->locker_size,
                        'time_slots' => $lockerSlots->map(fn($slot) => "{$slot->start_time} - {$slot->end_time}")->values()->all(),
                    ];
                })->values()->all();
        })->all();
    }

    public function cancelBooking($bookingSchedule): JsonResponse
    {
        try {
            $bookingSchedule = BookingSchedule::findOrFail($bookingSchedule);
            $now = Carbon::now();
            DB::beginTransaction();
            $bookingDate = $bookingSchedule->booking_date;
            $startTime = optional($bookingSchedule->timeSlot)->start_time;
            // dd($bookingDate, $startTime);
            $bookingDateTime = Carbon::createFromFormat('Y-m-d H:i:s', "{$bookingDate} {$startTime}");
            if ($now->greaterThanOrEqualTo($bookingDateTime)) {
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => 'Booking cannot be canceled as the time slot has already started or passed.'
                ], 400);
            }
            $bookingSchedule->update(['status' => 'Cancelled']);
            $bookingSchedule->delete();
            DB::commit();
            $response = [
                'success' => true,
                'data' => [],
                'message' => 'Booking Cancelled Successfully.'
            ];
            return response()->json($response, 200);
        } catch (Exception $e) {
            DB::rollBack();
            $data = [
                'success' => false,
                'data' => [],
                'message' => 'Resource not found.'
            ];
            return response()->json($data, 500);
        }
    }

    public function bookLocker($request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $existingBooking = BookingSchedule::where([
                'locker_station_id' => $request->locker_station_id,
                'locker_id' => $request->locker_id,
                'time_slot_id' => $request->time_slot_id,
                'booking_date' => $request->date,
                'status' => BookingSchedule::STATUS_ACTIVE
            ])->first();

            if ($existingBooking) {
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => 'Already booked.',
                ], 409);
            }

            $booking = BookingSchedule::create([
                'locker_station_id' => $request->locker_station_id,
                'locker_id' => $request->locker_id,
                'user_id' => $request->user_id,
                'time_slot_id' => $request->time_slot_id,
                'booking_date' => $request->date,
                'status' => BookingSchedule::STATUS_ACTIVE,
            ]);
            DB::commit();

            $booking->load('user', 'lockerStation', 'locker', 'timeSlot');
            $bookingData = LockerBookingResource::make($booking);
            $response = [
                'success' => true,
                'data' => $bookingData,
                'message' => 'Locker booked Successfully.'
            ];
            return response()->json($response);
        } catch (Exception $e) {

            DB::rollBack();
            $data = [
                'success' => false,
                'data' => [],
                'message' => 'Resource not found.'
            ];
            return response()->json($data, 500);
        }
    }
}