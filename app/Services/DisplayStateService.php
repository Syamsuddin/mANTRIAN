<?php

namespace App\Services;

use App\Models\Counter;
use App\Models\QueueCall;
use App\Models\Ticket;
use Carbon\CarbonImmutable;

class DisplayStateService
{
    public function state(?string $date = null): array
    {
        $ticketDate = $date ?: CarbonImmutable::now(config('app.timezone'))->toDateString();
        $latest = QueueCall::with('ticket')
            ->whereDate('called_at', $ticketDate)
            ->whereIn('event_type', ['call', 'recall'])
            ->latest('called_at')
            ->first();

        $counters = Counter::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function (Counter $counter) use ($ticketDate) {
                $call = QueueCall::with('ticket')
                    ->where('counter_id', $counter->id)
                    ->whereDate('called_at', $ticketDate)
                    ->whereHas('ticket', fn ($query) => $query->whereIn('status', Ticket::ACTIVE_STATUSES))
                    ->latest('called_at')
                    ->first();

                return [
                    'id' => $counter->id,
                    'name' => $counter->name,
                    'location' => $counter->location,
                    'current_ticket_no' => $call?->ticket?->ticket_no,
                    'service_name' => $call?->ticket?->service_name_snapshot,
                    'status' => $call?->ticket?->status,
                ];
            })
            ->all();

        return [
            'generated_at' => now()->toIso8601String(),
            'latest_call' => $latest ? [
                'ticket_no' => $latest->ticket->ticket_no,
                'service_name' => $latest->ticket->service_name_snapshot,
                'counter_name' => $latest->counter_name_snapshot,
                'called_at' => $latest->called_at->toIso8601String(),
            ] : null,
            'counters' => $counters,
        ];
    }
}
