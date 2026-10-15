<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Equipment;
use App\Services\BookingAvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function __construct(private BookingAvailabilityService $availability)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $bookings = Booking::with(['user', 'items.equipment'])
            ->when(! $user->isAdmin(), fn ($query) => $query->where('user_id', $user->id))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->string('scope')->toString() === 'upcoming', fn ($query) => $query->whereDate('event_date', '>=', today()))
            ->when($request->string('scope')->toString() === 'past', fn ($query) => $query->whereDate('event_date', '<', today()))
            ->orderByDesc('event_date')
            ->orderByDesc('start_time')
            ->paginate(10)
            ->withQueryString();

        return view('bookings.index', [
            'bookings' => $bookings,
            'statuses' => Booking::STATUSES,
        ]);
    }

    public function create()
    {
        $equipment = Equipment::active()
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('bookings.create', compact('equipment'));
    }

    public function store(StoreBookingRequest $request)
    {
        $data = $request->validated();

        $items = collect($data['items'])
            ->mapWithKeys(fn ($item) => [(int) $item['equipment_id'] => (int) $item['quantity']])
            ->all();

        $booking = DB::transaction(function () use ($data, $items, $request) {
            Equipment::whereIn('id', array_keys($items))->lockForUpdate()->get();

            $conflicts = $this->availability->conflicts(
                $items,
                $data['event_date'],
                $data['start_time'],
                $data['end_time']
            );

            if ($conflicts !== []) {
                throw ValidationException::withMessages([
                    'items' => $this->conflictMessages($conflicts),
                ]);
            }

            $booking = $request->user()->bookings()->create([
                'event_name' => $data['event_name'],
                'event_description' => $data['event_description'] ?? null,
                'venue' => $data['venue'] ?? null,
                'event_date' => $data['event_date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'notes' => $data['notes'] ?? null,
                'status' => 'confirmed',
            ]);

            foreach ($items as $equipmentId => $quantity) {
                $booking->items()->create([
                    'equipment_id' => $equipmentId,
                    'quantity' => $quantity,
                ]);
            }

            return $booking;
        });

        return redirect()
            ->route('bookings.show', $booking)
            ->with('status', 'Booking confirmed! No other group can reserve those items at the same time.');
    }

    public function show(Booking $booking)
    {
        $this->authorizeAccess($booking);

        $booking->load(['user', 'items.equipment']);

        return view('bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        $this->authorizeAccess($booking);

        if ($booking->status === 'cancelled') {
            return redirect()->route('bookings.show', $booking)->with('status', 'This booking is already cancelled.');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()
            ->route('bookings.show', $booking)
            ->with('status', 'Booking cancelled. The items are now available to others for that time.');
    }

    private function authorizeAccess(Booking $booking): void
    {
        $user = auth()->user();

        abort_unless($user->isAdmin() || $booking->user_id === $user->id, 403);
    }

    /**
     * @param  array<int, array<string, mixed>>  $conflicts
     * @return array<int, string>
     */
    private function conflictMessages(array $conflicts): array
    {
        return array_map(
            fn ($conflict) => sprintf(
                '%s: you requested %d but only %d of %d are still available for the selected date and time.',
                $conflict['name'],
                $conflict['requested'],
                $conflict['available'],
                $conflict['total'],
            ),
            $conflicts
        );
    }
}
