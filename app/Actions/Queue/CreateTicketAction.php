<?php

namespace App\Actions\Queue;

use App\Models\DailySequence;
use App\Models\Service;
use App\Models\Ticket;
use App\Services\AuditLogger;
use App\Services\OperatingHoursService;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateTicketAction
{
    public function __construct(
        private readonly AuditLogger $audit,
        private readonly OperatingHoursService $hours,
    ) {
    }

    public function execute(Service $service, ?Request $request = null): Ticket
    {
        if (! $service->is_active) {
            throw ValidationException::withMessages(['service_id' => 'Layanan tidak aktif.']);
        }

        if (! $this->hours->isOpen()) {
            throw ValidationException::withMessages(['service_id' => 'Layanan sedang tutup.']);
        }

        $today = CarbonImmutable::now(config('app.timezone'))->toDateString();

        return DB::transaction(function () use ($service, $today, $request) {
            DailySequence::query()->firstOrCreate(
                ['service_id' => $service->id, 'sequence_date' => $today],
                ['last_number' => 0]
            );

            $sequence = DailySequence::query()
                ->where('service_id', $service->id)
                ->whereDate('sequence_date', $today)
                ->lockForUpdate()
                ->firstOrFail();

            $sequence->increment('last_number');
            $number = $sequence->last_number;

            $ticket = Ticket::create([
                'service_id' => $service->id,
                'ticket_date' => $today,
                'sequence_no' => $number,
                'ticket_no' => $service->prefix.str_pad((string) $number, 3, '0', STR_PAD_LEFT),
                'service_name_snapshot' => $service->name,
                'status' => 'waiting',
                'meta' => ['ip' => $request?->ip()],
            ]);

            $this->audit->log('ticket.created', $ticket, metadata: [
                'ticket_no' => $ticket->ticket_no,
                'service_id' => $service->id,
            ], request: $request);

            return $ticket;
        });
    }
}
