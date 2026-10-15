<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    /** @use HasFactory<\Database\Factories\EquipmentFactory> */
    use HasFactory;

    protected $table = 'equipment';

    public const CATEGORIES = [
        'chair' => 'Chairs',
        'table' => 'Tables',
        'sound_system' => 'Sound System',
        'projector' => 'Projector',
        'other' => 'Other',
    ];

    public const STATUSES = [
        'available' => 'Available',
        'maintenance' => 'Under Maintenance',
        'retired' => 'Retired',
    ];

    protected $fillable = [
        'name',
        'category',
        'description',
        'quantity',
        'status',
        'location',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst($this->category);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getIsBookableAttribute(): bool
    {
        return $this->status === 'available';
    }

    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'retired');
    }
}
