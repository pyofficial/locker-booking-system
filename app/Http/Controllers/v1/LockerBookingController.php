<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LockerBookingRequest;
use App\Repositories\LockerBookingsRepository;
use Illuminate\Http\JsonResponse;

class LockerBookingController extends Controller
{

    protected $lockerBooking;

    public function __construct(LockerBookingsRepository $lockerBookingsRepository)
    {
        $this->lockerBooking = $lockerBookingsRepository;
    }

    public function checkAvailability(LockerBookingRequest $request): JsonResponse
    {
        return $this->lockerBooking->getAvailableSlots($request);
    }

    public function bookLocker(LockerBookingRequest $request): JsonResponse
    {
        return $this->lockerBooking->bookLocker($request);
    }

    public function cancelBooking($bookingSchedule): JsonResponse
    {
        return $this->lockerBooking->cancelBooking($bookingSchedule);
    }
}
