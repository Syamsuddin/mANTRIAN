<?php

namespace Tests\Feature;

use App\Actions\Queue\CallNextTicketAction;
use App\Actions\Queue\CreateTicketAction;
use App\Models\Counter;
use App\Models\CounterAssignment;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class QueueWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_kiosk_creates_unique_daily_ticket_numbers(): void
    {
        $service = Service::create([
            'code' => 'ADM',
            'name' => 'Administrasi',
            'prefix' => 'A',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $first = app(CreateTicketAction::class)->execute($service);
        $second = app(CreateTicketAction::class)->execute($service);

        $this->assertSame('A001', $first->ticket_no);
        $this->assertSame('A002', $second->ticket_no);
        $this->assertDatabaseHas('audit_logs', ['action' => 'ticket.created']);
    }

    public function test_operator_calls_next_ticket_for_assigned_counter(): void
    {
        [$operator, $counter, $service] = $this->seedOperatorFixture();
        app(CreateTicketAction::class)->execute($service);

        $ticket = app(CallNextTicketAction::class)->execute($operator, $counter);

        $this->assertSame('serving', $ticket->status);
        $this->assertDatabaseHas('queue_calls', [
            'ticket_id' => $ticket->id,
            'counter_id' => $counter->id,
            'event_type' => 'call',
        ]);
    }

    public function test_operator_cannot_call_when_counter_has_active_ticket(): void
    {
        [$operator, $counter, $service] = $this->seedOperatorFixture();
        app(CreateTicketAction::class)->execute($service);
        app(CreateTicketAction::class)->execute($service);
        app(CallNextTicketAction::class)->execute($operator, $counter);

        $this->expectException(\RuntimeException::class);
        app(CallNextTicketAction::class)->execute($operator, $counter);
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::create([
            'name' => 'Inactive',
            'email' => 'inactive@example.test',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'is_active' => false,
        ]);

        $this->post('/login', ['email' => 'inactive@example.test', 'password' => 'password'])
            ->assertSessionHasErrors('email');
    }

    private function seedOperatorFixture(): array
    {
        $operator = User::create([
            'name' => 'Operator',
            'email' => 'operator@example.test',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'is_active' => true,
        ]);
        $service = Service::create(['code' => 'ADM', 'name' => 'Administrasi', 'prefix' => 'A', 'is_active' => true, 'sort_order' => 1]);
        $counter = Counter::create(['code' => 'LK-01', 'name' => 'Loket 1', 'is_active' => true, 'sort_order' => 1]);
        $counter->services()->sync([$service->id]);
        CounterAssignment::create(['user_id' => $operator->id, 'counter_id' => $counter->id, 'start_at' => now(), 'is_active' => true]);

        return [$operator, $counter, $service];
    }
}
