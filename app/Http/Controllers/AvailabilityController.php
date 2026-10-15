<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Services\BookingAvailabilityService;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function __invoke(Request $request, BookingAvailabilityService $availability)
    {
        $data = $request->validate([
            'equipment_id' => ['required', 'integer', 'exists:equipment,id'],
            'event_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'exclude' => ['nullable', 'integer'],
        ]);

        $equipment = Equipment::findOrFail($data['equipment_id']);

        $available = $availability->availableQuantity(
            $equipment,
            $data['event_date'],
            $data['start_time'],
            $data['end_time'],
            $data['exclude'] ?? null,
        );

        return response()->json([
            'equipment_id' => $equipment->id,
            'name' => $equipment->name,
            'total' => $equipment->quantity,
            'booked' => $equipment->quantity - $available,
            'available' => $available,
        ]);
    }
}
