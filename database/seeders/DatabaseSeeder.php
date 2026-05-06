<?php

namespace Database\Seeders;

use App\Models\Counter;
use App\Models\CounterAssignment;
use App\Models\OperatingHour;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@mantrian.test'],
            ['name' => 'Admin mANTRIAN', 'password' => Hash::make('password'), 'role' => 'super_admin', 'is_active' => true]
        );

        $supervisor = User::updateOrCreate(
            ['email' => 'supervisor@mantrian.test'],
            ['name' => 'Supervisor', 'password' => Hash::make('password'), 'role' => 'supervisor', 'is_active' => true]
        );

        $operator = User::updateOrCreate(
            ['email' => 'operator@mantrian.test'],
            ['name' => 'Operator Loket 1', 'password' => Hash::make('password'), 'role' => 'operator', 'is_active' => true]
        );

        $administrasi = Service::updateOrCreate(
            ['code' => 'ADM'],
            ['name' => 'Administrasi', 'prefix' => 'A', 'description' => 'Layanan administrasi umum', 'is_active' => true, 'sort_order' => 1]
        );

        $konsultasi = Service::updateOrCreate(
            ['code' => 'KON'],
            ['name' => 'Konsultasi', 'prefix' => 'B', 'description' => 'Konsultasi dan informasi', 'is_active' => true, 'sort_order' => 2]
        );

        $counter = Counter::updateOrCreate(
            ['code' => 'LK-01'],
            ['name' => 'Loket 1', 'location' => 'Ruang Pelayanan', 'is_active' => true, 'sort_order' => 1]
        );
        $counter->services()->sync([$administrasi->id, $konsultasi->id]);

        CounterAssignment::updateOrCreate(
            ['user_id' => $operator->id, 'counter_id' => $counter->id],
            ['start_at' => now(), 'end_at' => null, 'is_active' => true]
        );

        for ($day = 1; $day <= 7; $day++) {
            OperatingHour::updateOrCreate(
                ['day_of_week' => $day],
                ['open_time' => '08:00:00', 'close_time' => '16:00:00', 'is_closed' => $day === 7]
            );
        }
    }
}
