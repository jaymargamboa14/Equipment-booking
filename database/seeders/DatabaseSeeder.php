<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@school.test'],
            ['name' => 'School Admin', 'password' => Hash::make('password'), 'role' => 'admin'],
        );

        $teachers = collect([
            ['name' => 'Ms. Rivera', 'email' => 'rivera@school.test'],
            ['name' => 'Mr. Santos', 'email' => 'santos@school.test'],
            ['name' => 'Science Club', 'email' => 'science@school.test'],
        ])->map(fn ($data) => User::updateOrCreate(
            ['email' => $data['email']],
            ['name' => $data['name'], 'password' => Hash::make('password'), 'role' => 'user'],
        ));

        $catalog = [
            ['name' => 'Monobloc Chairs', 'category' => 'chair', 'quantity' => 250, 'location' => 'Gym Storage', 'description' => 'White plastic monobloc chairs for audiences.'],
            ['name' => 'Foldable Chairs', 'category' => 'chair', 'quantity' => 80, 'location' => 'Storage Room A', 'description' => 'Padded folding chairs for VIP guests.'],
            ['name' => 'Banquet Tables', 'category' => 'table', 'quantity' => 40, 'location' => 'Gym Storage', 'description' => 'Rectangular folding tables, 6 ft.'],
            ['name' => 'Round Tables', 'category' => 'table', 'quantity' => 20, 'location' => 'Storage Room B', 'description' => 'Round tables ideal for receptions.'],
            ['name' => 'Portable Sound System', 'category' => 'sound_system', 'quantity' => 3, 'location' => 'AV Room', 'description' => 'Complete PA system with speakers, mixer and 2 microphones.'],
            ['name' => 'Wireless Microphones', 'category' => 'sound_system', 'quantity' => 8, 'location' => 'AV Room', 'description' => 'Handheld wireless microphones with receivers.'],
            ['name' => 'LCD Projector (Full HD)', 'category' => 'projector', 'quantity' => 5, 'location' => 'AV Room', 'description' => '3000-lumen projector with HDMI and VGA inputs.'],
            ['name' => 'Projector Screen', 'category' => 'projector', 'quantity' => 6, 'location' => 'AV Room', 'description' => 'Tripod projector screen, 70 inch.', 'status' => 'available'],
            ['name' => 'Stage Lights', 'category' => 'other', 'quantity' => 4, 'location' => 'AV Room', 'description' => 'LED stage lighting set.'],
        ];

        $equipment = collect($catalog)->mapWithKeys(function ($data) {
            $item = Equipment::updateOrCreate(
                ['name' => $data['name']],
                array_merge([
                    'category' => 'other',
                    'quantity' => 1,
                    'status' => 'available',
                ], $data),
            );

            return [$item->name => $item];
        });

        $sampleBookings = [
            [
                'user' => $teachers[0],
                'event_name' => 'Foundation Day Program',
                'event_description' => 'Annual school foundation day celebration.',
                'venue' => 'School Gymnasium',
                'event_date' => now()->addDays(5)->toDateString(),
                'start_time' => '08:00',
                'end_time' => '12:00',
                'items' => [
                    ['name' => 'Monobloc Chairs', 'quantity' => 150],
                    ['name' => 'Portable Sound System', 'quantity' => 1],
                    ['name' => 'LCD Projector (Full HD)', 'quantity' => 2],
                ],
            ],
            [
                'user' => $teachers[1],
                'event_name' => 'Recognition Rites',
                'event_description' => 'Awarding ceremony for honor students.',
                'venue' => 'Covered Court',
                'event_date' => now()->addDays(6)->toDateString(),
                'start_time' => '13:00',
                'end_time' => '17:00',
                'items' => [
                    ['name' => 'Foldable Chairs', 'quantity' => 60],
                    ['name' => 'Wireless Microphones', 'quantity' => 4],
                ],
            ],
            [
                'user' => $teachers[2],
                'event_name' => 'Science Fair',
                'event_description' => 'Exhibit of student science projects.',
                'venue' => 'Science Laboratory',
                'event_date' => now()->addDays(8)->toDateString(),
                'start_time' => '09:00',
                'end_time' => '15:00',
                'items' => [
                    ['name' => 'Banquet Tables', 'quantity' => 15],
                    ['name' => 'Monobloc Chairs', 'quantity' => 30],
                    ['name' => 'Projector Screen', 'quantity' => 2],
                ],
            ],
        ];

        foreach ($sampleBookings as $data) {
            $booking = Booking::create([
                'user_id' => $data['user']->id,
                'event_name' => $data['event_name'],
                'event_description' => $data['event_description'],
                'venue' => $data['venue'],
                'event_date' => $data['event_date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'status' => 'confirmed',
            ]);

            foreach ($data['items'] as $line) {
                $booking->items()->create([
                    'equipment_id' => $equipment[$line['name']]->id,
                    'quantity' => $line['quantity'],
                ]);
            }
        }
    }
}
