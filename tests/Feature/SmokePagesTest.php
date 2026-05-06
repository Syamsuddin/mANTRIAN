<?php

namespace Tests\Feature;

use App\Models\Counter;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SmokePagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_kiosk_display_and_login_pages_render(): void
    {
        Service::create(['code' => 'ADM', 'name' => 'Administrasi', 'prefix' => 'A', 'is_active' => true, 'sort_order' => 1]);
        Counter::create(['code' => 'LK-01', 'name' => 'Loket 1', 'is_active' => true, 'sort_order' => 1]);

        $this->get('/kiosk')->assertOk()->assertSee('Pilih Layanan');
        $this->get('/display')->assertOk()->assertSee('mANTRIAN');
        $this->get('/login')->assertOk()->assertSee('Login');
        $this->get('/api/display/state')->assertOk()->assertJsonStructure(['generated_at', 'latest_call', 'counters']);
    }

    public function test_admin_dashboard_renders_for_admin(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->get('/admin/dashboard')->assertOk()->assertSee('Dashboard Hari Ini');
    }
}
