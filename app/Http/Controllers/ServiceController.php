<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Service;
use App\Repositories\BookingRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class ServiceController extends Controller
{
    private BookingRepository $bookings;

    public function __construct(BookingRepository $bookings)
    {
        $this->bookings = $bookings;
    }

    /**
     * Главная страница — список всех услуг
     */
    public function index()
    {
        $services = Service::all();

        return Inertia::render('Services/Index', [
            'services' => $services,
        ]);
    }

    /**
     * Просмотр конкретной услуги
     */
    public function show(Service $service)
    {
        return Inertia::render('Services/Show', [
            'service' => $service,
        ]);
    }

    /**
     * Получение списка доступных слотов на указанную дату
     */
    public function slots(Service $service, Request $request)
    {
        $date = Carbon::parse($request->get('date'));
        $slots = [];

        if ($date->isSunday()) {
            return response()->json([]);
        }

        $current = $date->copy()->setTime(10, 0);
        $end = $date->copy()->setTime(20, 0);

        while ($current < $end) {
            if ($this->bookings->isAvailable($service, $current)) {
                $slots[] = $current->format('H:i');
            }
            $current->addMinutes(30);
        }

        return response()->json($slots);
    }
}
