<?php

namespace App\Actions\Queue;

use App\Models\Counter;
use App\Models\QueueCall;
use App\Models\Ticket;
use App\Models\User;
use App\Services\AuditLogger;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CallNextTicketAction
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function execute(User $operator, Counter $counter): Ticket
    {
        return DB::transaction(function () use ($operator, $counter) {
            $this->authorize($operator, $counter);

            $hasActive = QueueCall::query()
                ->where('counter_id', $counter->id)
                ->whereHas('ticket', fn ($query) => $query->whereIn('status', Ticket::ACTIVE_STATUSES))
                ->lockForUpdate()
                ->exists();

            if ($hasActive) {
                throw new RuntimeException('Loket masih memiliki tiket aktif.');
            }

            $serviceIds = $counter->services()->where('services.is_active', true)->pluck('services.id');

            $ticket = Ticket::query()
                ->where('status', 'waiting')
                ->whereDate('ticket_date', CarbonImmutable::now(config('app.timezone'))->toDateString())
                ->whereIn('service_id', $serviceIds)
                ->orderBy('created_at')
                ->orderBy('id')
                ->lockForUpdate()
                ->first();

            if (! $ticket) {
                throw new RuntimeException('Belum ada antrian menunggu.');
            }

            $ticket->update([
                'status' => 'serving',
                'called_at' => now(),
                'started_at' => now(),
            ]);

            $this->recordCall($ticket, $counter, $operator, 'call');
            $this->audit->log('queue.called', $ticket, metadata: ['counter_id' => $counter->id]);

            return $ticket->refresh();
        });
    }

    private function authorize(User $operator, Counter $counter): void
    {
        $assigned = $operator->activeAssignments()->where('counter_id', $counter->id)->exists();

        if ($operator->role !== 'operator' || ! $assigned || ! $operator->is_active || ! $counter->is_active) {
            throw new AuthorizationException('Operator tidak ditugaskan pada loket ini.');
        }
    }

    private function recordCall(Ticket $ticket, Counter $counter, User $operator, string $event, ?string $notes = null): QueueCall
    {
        $callNo = QueueCall::where('ticket_id', $ticket->id)->max('call_no') + 1;

        return QueueCall::create([
            'ticket_id' => $ticket->id,
            'counter_id' => $counter->id,
            'operator_id' => $operator->id,
            'call_no' => $callNo,
            'event_type' => $event,
            'counter_name_snapshot' => $counter->name,
            'operator_name_snapshot' => $operator->name,
            'called_at' => now(),
            'notes' => $notes,
        ]);
    }
}
