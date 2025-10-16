<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Service;
use App\Repositories\BookingRepository;
use Illuminate\Http\Request;
use Throwable;

class BookingController extends Controller
{
    private BookingRepository $bookings;

    public function __construct(BookingRepository $bookings)
    {
        $this->bookings = $bookings;
    }

    public function store(Request $request, Service $service)
    {
        $validated = $request->validate([
            'client_name'  => 'required|string|max:255',
            'client_phone' => 'required|string|max:50',
            'start_at'     => 'required|date_format:Y-m-d\TH:i',
        ]);

        try {
            $booking = $this->bookings->create($service, $validated);
            return response()->json(['success' => true, 'booking' => $booking]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
