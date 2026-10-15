<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $upcoming = Booking::with(['user', 'items.equipment'])
            ->occupying()
            ->whereDate('event_date', '>=', today())
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->when(! $user->isAdmin(), fn ($query) => $query->where('user_id', $user->id))
            ->limit(5)
            ->get();

        $stats = [
            'equipment_types' => Equipment::active()->count(),
            'total_items' => (int) Equipment::active()->sum('quantity'),
            'my_bookings' => Booking::where('user_id', $user->id)->count(),
            'upcoming' => Booking::occupying()->whereDate('event_date', '>=', today())->count(),
            'all_bookings' => Booking::count(),
            'users' => $user->isAdmin() ? \App\Models\User::count() : null,
        ];

        return view('dashboard', compact('stats', 'upcoming'));
    }
}
