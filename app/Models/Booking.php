<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    /**
     * Атрибуты, доступные для массового заполнения.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_id',
        'client_name',
        'client_phone',
        'start_at',
        'end_at',
    ];

    /**
     * Касты для полей.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    /**
     * Связь: бронирование принадлежит услуге.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
