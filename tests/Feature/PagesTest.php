<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function bookingFor(User $user): Booking
    {
        $equipment = Equipment::create([
            'name' => 'LCD Projector (Full HD)',
            'category' => 'projector',
            'quantity' => 5,
            'status' => 'available',
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'event_name' => 'Foundation Day',
            'event_date' => now()->addDays(4)->toDateString(),
            'start_time' => '08:00',
            'end_time' => '12:00',
            'status' => 'confirmed',
        ]);

        $booking->items()->create([
            'equipment_id' => $equipment->id,
            'quantity' => 2,
        ]);

        return $booking;
    }

    public function test_landing_page_renders(): void
    {
        $this->get('/')->assertOk()->assertSee('School Event Equipment Booking System');
    }

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_admin_can_view_all_main_pages(): void
    {
        $admin = $this->admin();
        $booking = $this->bookingFor($admin);
        $equipment = Equipment::first();

        $this->actingAs($admin)->get('/dashboard')->assertOk();
        $this->actingAs($admin)->get('/equipment')->assertOk();
        $this->actingAs($admin)->get('/equipment/create')->assertOk();
        $this->actingAs($admin)->get('/equipment/'.$equipment->id)->assertOk();
        $this->actingAs($admin)->get('/equipment/'.$equipment->id.'/edit')->assertOk();
        $this->actingAs($admin)->get('/bookings')->assertOk();
        $this->actingAs($admin)->get('/bookings/create')->assertOk();
        $this->actingAs($admin)->get('/bookings/'.$booking->id)->assertOk();
    }

    public function test_user_can_view_own_booking_but_not_others(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $booking = $this->bookingFor($owner);

        $this->actingAs($owner)->get('/bookings/'.$booking->id)->assertOk();
        $this->actingAs($other)->get('/bookings/'.$booking->id)->assertForbidden();
    }

    public function test_booking_form_preselects_equipment(): void
    {
        $admin = $this->admin();
        $equipment = Equipment::create([
            'name' => 'Monobloc Chairs',
            'category' => 'chair',
            'quantity' => 250,
            'status' => 'available',
        ]);

        $this->actingAs($admin)
            ->get('/bookings/create?equipment='.$equipment->id)
            ->assertOk()
            ->assertSee('data-preselect="'.$equipment->id.'"', false);
    }
}
