<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

    public const STATUSES = [
        'confirmed' => 'Confirmed',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    protected $fillable = [
        'user_id',
        'event_name',
        'event_description',
        'venue',
        'event_date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function equipment(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'booking_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function scopeOccupying($query)
    {
        return $query->whereIn('status', ['confirmed', 'completed']);
    }

    public function getIsCancelledAttribute(): bool
    {
        return $this->status === 'cancelled';
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getStartsAtAttribute(): \Carbon\Carbon
    {
        return $this->event_date->copy()->setTimeFromTimeString($this->start_time);
    }

    public function getEndsAtAttribute(): \Carbon\Carbon
    {
        return $this->event_date->copy()->setTimeFromTimeString($this->end_time);
    }
}
