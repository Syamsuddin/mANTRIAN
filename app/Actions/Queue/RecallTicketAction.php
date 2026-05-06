<?php

namespace App\Actions\Queue;

use App\Models\Counter;
use App\Models\QueueCall;
use App\Models\Ticket;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;

class RecallTicketAction
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function execute(User $operator, Counter $counter, Ticket $ticket): void
    {
        $this->guard($operator, $counter, $ticket);
        $callNo = QueueCall::where('ticket_id', $ticket->id)->max('call_no') + 1;

        QueueCall::create([
            'ticket_id' => $ticket->id,
            'counter_id' => $counter->id,
            'operator_id' => $operator->id,
            'call_no' => $callNo,
            'event_type' => 'recall',
            'counter_name_snapshot' => $counter->name,
            'operator_name_snapshot' => $operator->name,
            'called_at' => now(),
        ]);

        $this->audit->log('queue.recalled', $ticket, metadata: ['counter_id' => $counter->id, 'call_no' => $callNo]);
    }

    private function guard(User $operator, Counter $counter, Ticket $ticket): void
    {
        if (! $operator->activeAssignments()->where('counter_id', $counter->id)->exists()) {
            throw new AuthorizationException();
        }

        $isCurrent = QueueCall::where('ticket_id', $ticket->id)->where('counter_id', $counter->id)->exists();

        if (! $isCurrent || ! in_array($ticket->status, Ticket::ACTIVE_STATUSES, true)) {
            throw ValidationException::withMessages(['ticket' => 'Tiket bukan tiket aktif loket ini.']);
        }
    }
}
