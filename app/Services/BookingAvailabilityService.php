<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Equipment;

class BookingAvailabilityService
{
    /**
     * Total quantity of an equipment already reserved during a given
     * date/time window by non-cancelled bookings.
     */
    public function bookedQuantity(
        Equipment $equipment,
        string $date,
        string $startTime,
        string $endTime,
        ?int $excludeBookingId = null
    ): int {
        return (int) BookingItem::query()
            ->where('equipment_id', $equipment->id)
            ->whereHas('booking', function ($query) use ($date, $startTime, $endTime, $excludeBookingId) {
                $query->occupying()
                    ->whereDate('event_date', $date)
                    ->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime)
                    ->when($excludeBookingId, fn ($q) => $q->where('id', '!=', $excludeBookingId));
            })
            ->sum('quantity');
    }

    /**
     * Remaining bookable quantity of an item for a given time window.
     */
    public function availableQuantity(
        Equipment $equipment,
        string $date,
        string $startTime,
        string $endTime,
        ?int $excludeBookingId = null
    ): int {
        $booked = $this->bookedQuantity($equipment, $date, $startTime, $endTime, $excludeBookingId);

        return max(0, $equipment->quantity - $booked);
    }

    /**
     * Given requested items (equipment_id => quantity), return the list of
     * items that cannot be fulfilled for the requested window.
     *
     * @param  array<int, int>  $requested  equipment id => quantity
     * @return array<int, array<string, mixed>>
     */
    public function conflicts(array $requested, string $date, string $startTime, string $endTime, ?int $excludeBookingId = null): array
    {
        $conflicts = [];

        $equipment = Equipment::whereIn('id', array_keys($requested))->get()->keyBy('id');

        foreach ($requested as $equipmentId => $quantity) {
            $item = $equipment->get($equipmentId);

            if (! $item) {
                continue;
            }

            $available = $this->availableQuantity($item, $date, $startTime, $endTime, $excludeBookingId);

            if ($quantity > $available) {
                $conflicts[] = [
                    'equipment_id' => $item->id,
                    'name' => $item->name,
                    'requested' => (int) $quantity,
                    'available' => $available,
                    'total' => $item->quantity,
                ];
            }
        }

        return $conflicts;
    }
}
