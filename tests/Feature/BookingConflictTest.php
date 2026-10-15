<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingConflictTest extends TestCase
{
    use RefreshDatabase;

    private function equipment(int $quantity = 100): Equipment
    {
        return Equipment::create([
            'name' => 'Monobloc Chairs',
            'category' => 'chair',
            'quantity' => $quantity,
            'status' => 'available',
        ]);
    }

    private function payload(Equipment $equipment, int $quantity, string $date, string $start, string $end, string $name = 'Event'): array
    {
        return [
            'event_name' => $name,
            'event_date' => $date,
            'start_time' => $start,
            'end_time' => $end,
            'items' => [
                ['equipment_id' => $equipment->id, 'quantity' => $quantity],
            ],
        ];
    }

    public function test_a_group_can_book_available_equipment(): void
    {
        $user = User::factory()->create();
        $equipment = $this->equipment(100);

        $response = $this->actingAs($user)->post(route('bookings.store'), $this->payload(
            $equipment, 40, now()->addDays(3)->toDateString(), '08:00', '12:00'
        ));

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', ['event_name' => 'Event']);
        $this->assertDatabaseHas('booking_items', ['equipment_id' => $equipment->id, 'quantity' => 40]);
    }

    public function test_two_groups_cannot_overbook_the_same_item_at_the_same_time(): void
    {
        $date = now()->addDays(3)->toDateString();
        $equipment = $this->equipment(100);

        $this->actingAs(User::factory()->create())
            ->post(route('bookings.store'), $this->payload($equipment, 70, $date, '08:00', '12:00'))
            ->assertRedirect();

        $response = $this->actingAs(User::factory()->create())
            ->post(route('bookings.store'), $this->payload($equipment, 70, $date, '09:00', '11:00'));

        $response->assertSessionHasErrors('items');
        $this->assertDatabaseCount('bookings', 1);
    }

    public function test_booking_is_allowed_when_remaining_stock_is_enough(): void
    {
        $date = now()->addDays(3)->toDateString();
        $equipment = $this->equipment(100);

        $this->actingAs(User::factory()->create())
            ->post(route('bookings.store'), $this->payload($equipment, 70, $date, '08:00', '12:00'));

        $response = $this->actingAs(User::factory()->create())
            ->post(route('bookings.store'), $this->payload($equipment, 30, $date, '09:00', '11:00'));

        $response->assertRedirect();
        $this->assertDatabaseCount('bookings', 2);
    }

    public function test_non_overlapping_times_do_not_conflict(): void
    {
        $date = now()->addDays(3)->toDateString();
        $equipment = $this->equipment(100);

        $this->actingAs(User::factory()->create())
            ->post(route('bookings.store'), $this->payload($equipment, 100, $date, '08:00', '10:00'));

        $response = $this->actingAs(User::factory()->create())
            ->post(route('bookings.store'), $this->payload($equipment, 100, $date, '10:00', '12:00'));

        $response->assertRedirect();
        $this->assertDatabaseCount('bookings', 2);
    }

    public function test_cancelled_bookings_free_up_equipment(): void
    {
        $date = now()->addDays(3)->toDateString();
        $equipment = $this->equipment(100);

        $first = User::factory()->create();
        $this->actingAs($first)->post(route('bookings.store'), $this->payload($equipment, 100, $date, '08:00', '12:00'));

        $booking = Booking::first();
        $this->actingAs($first)->patch(route('bookings.cancel', $booking))->assertRedirect();

        $response = $this->actingAs(User::factory()->create())
            ->post(route('bookings.store'), $this->payload($equipment, 100, $date, '09:00', '11:00'));

        $response->assertRedirect();
        $this->assertDatabaseCount('bookings', 2);
    }

    public function test_a_user_cannot_cancel_another_users_booking(): void
    {
        $date = now()->addDays(3)->toDateString();
        $equipment = $this->equipment(50);

        $owner = User::factory()->create();
        $this->actingAs($owner)->post(route('bookings.store'), $this->payload($equipment, 10, $date, '08:00', '12:00'));

        $booking = Booking::first();
        $this->actingAs(User::factory()->create())->patch(route('bookings.cancel', $booking))->assertForbidden();
    }

    public function test_admin_can_manage_equipment_but_regular_users_cannot(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('equipment.store'), [
            'name' => 'Portable Sound System',
            'category' => 'sound_system',
            'quantity' => 3,
            'status' => 'available',
        ])->assertRedirect();

        $this->assertDatabaseHas('equipment', ['name' => 'Portable Sound System']);

        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($admin)->get(route('equipment.create'))->assertOk();
        $this->actingAs($user)->get(route('equipment.create'))->assertForbidden();
    }
}
