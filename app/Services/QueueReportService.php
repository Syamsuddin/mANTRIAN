<?php

namespace App\Services;

use App\Models\Ticket;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class QueueReportService
{
    public function summary(?string $date = null): array
    {
        $date ??= CarbonImmutable::now(config('app.timezone'))->toDateString();

        return Ticket::query()
            ->select('status', DB::raw('count(*) as total'))
            ->whereDate('ticket_date', $date)
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();
    }

    public function byService(?string $date = null)
    {
        $date ??= CarbonImmutable::now(config('app.timezone'))->toDateString();

        return Ticket::query()
            ->select('service_name_snapshot', 'status', DB::raw('count(*) as total'))
            ->whereDate('ticket_date', $date)
            ->groupBy('service_name_snapshot', 'status')
            ->orderBy('service_name_snapshot')
            ->get();
    }
}
