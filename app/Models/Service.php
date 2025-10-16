<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    /**
     * Атрибуты, доступные для массового заполнения.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'duration_minutes',
        'working_days',
        'work_start',
        'work_end',
    ];

    /**
     * Касты для полей.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'working_days' => 'array',
    ];

    /**
     * Связь: услуга имеет множество бронирований.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Проверка, работает ли услуга в указанный день недели.
     */
    public function worksOnDay(int $dayOfWeek): bool
    {
        $days = $this->working_days ?? [1, 2, 3, 4, 5, 6];
        return in_array($dayOfWeek, $days, true);
    }
}
