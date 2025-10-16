<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Booking;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingRepository
{
    /**
     * Проверяет, доступен ли слот для бронирования.
     */
    public function isAvailable(Service $service, Carbon $startAt): bool
    {
        $endAt = (clone $startAt)->addMinutes($service->duration_minutes + 30);

        // нельзя в воскресенье
        if ($startAt->isSunday()) {
            return false;
        }

        // проверяем рабочие часы
        if ($startAt->hour < 10 || $endAt->hour >= 20) {
            return false;
        }

        // проверяем наличие пересечений
        $conflict = Booking::where('service_id', $service->id)
            ->where(function ($q) use ($startAt, $endAt) {
                $q->whereBetween('start_at', [$startAt, $endAt])
                    ->orWhereBetween('end_at', [$startAt, $endAt])
                    ->orWhere(function ($q2) use ($startAt, $endAt) {
                        $q2->where('start_at', '<=', $startAt)
                            ->where('end_at', '>=', $endAt);
                    });
            })
            ->exists();

        return !$conflict;
    }

    /**
     * Пытается создать бронь с защитой от race-condition.
     */
    public function create(Service $service, array $data): Booking
    {
        $startAt = Carbon::parse($data['start_at']);
        $endAt = (clone $startAt)->addMinutes($service->duration_minutes + 30);

        return DB::transaction(function () use ($service, $startAt, $endAt, $data) {
            $conflict = Booking::where('service_id', $service->id)
                ->where(function ($q) use ($startAt, $endAt) {
                    $q->whereBetween('start_at', [$startAt, $endAt])
                        ->orWhereBetween('end_at', [$startAt, $endAt])
                        ->orWhere(function ($q2) use ($startAt, $endAt) {
                            $q2->where('start_at', '<=', $startAt)
                                ->where('end_at', '>=', $endAt);
                        });
                })
                ->lockForUpdate()
                ->exists();

            if ($conflict) {
                throw new \RuntimeException('Выбранный слот уже занят');
            }

            return Booking::create([
                'service_id'   => $service->id,
                'client_name'  => $data['client_name'],
                'client_phone' => $data['client_phone'],
                'start_at'     => $startAt,
                'end_at'       => $endAt,
            ]);
        });
    }
}
