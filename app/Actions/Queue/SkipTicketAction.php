<?php

namespace App\Actions\Queue;

use App\Models\Counter;
use App\Models\QueueCall;
use App\Models\Ticket;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SkipTicketAction
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function execute(User $operator, Counter $counter, Ticket $ticket, ?string $reason = null): Ticket
    {
        return DB::transaction(function () use ($operator, $counter, $ticket, $reason) {
            $this->guard($operator, $counter, $ticket);

            $ticket->update(['status' => 'skipped', 'skipped_at' => now()]);
            QueueCall::create([
                'ticket_id' => $ticket->id,
                'counter_id' => $counter->id,
                'operator_id' => $operator->id,
                'call_no' => QueueCall::where('ticket_id', $ticket->id)->max('call_no') + 1,
                'event_type' => 'skip',
                'counter_name_snapshot' => $counter->name,
                'operator_name_snapshot' => $operator->name,
                'called_at' => now(),
                'notes' => $reason,
            ]);
            $this->audit->log('queue.skipped', $ticket, metadata: ['reason' => $reason]);

            return $ticket->refresh();
        });
    }

    private function guard(User $operator, Counter $counter, Ticket $ticket): void
    {
        if (! $operator->activeAssignments()->where('counter_id', $counter->id)->exists()) {
            throw new AuthorizationException();
        }

        if (! in_array($ticket->status, Ticket::ACTIVE_STATUSES, true)) {
            throw ValidationException::withMessages(['ticket' => 'Tiket tidak aktif.']);
        }
    }
}
