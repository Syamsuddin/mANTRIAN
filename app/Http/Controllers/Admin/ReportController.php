<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Ticket;
use App\Services\QueueReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function queues(Request $request, QueueReportService $reports)
    {
        $date = $request->query('date', now()->toDateString());

        return view('admin.reports.queues', [
            'date' => $date,
            'summary' => $reports->summary($date),
            'tickets' => Ticket::whereDate('ticket_date', $date)->latest()->paginate(50),
        ]);
    }

    public function audit()
    {
        return view('admin.audit.index', [
            'logs' => AuditLog::with('actor')->latest('created_at')->paginate(50),
        ]);
    }
}
