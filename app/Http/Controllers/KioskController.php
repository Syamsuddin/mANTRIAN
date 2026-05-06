<?php

namespace App\Http\Controllers;

use App\Actions\Queue\CreateTicketAction;
use App\Models\Service;
use App\Models\Ticket;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class KioskController extends Controller
{
    public function index()
    {
        return view('kiosk.index', [
            'services' => Service::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, CreateTicketAction $action)
    {
        $data = $request->validate(['service_id' => ['required', 'exists:services,id']]);
        $ticket = $action->execute(Service::findOrFail($data['service_id']), $request);

        return redirect()->route('kiosk.tickets.show', $ticket);
    }

    public function show(Ticket $ticket, AuditLogger $audit)
    {
        if (! $ticket->printed_at) {
            $ticket->update(['printed_at' => now()]);
            $audit->log('ticket.printed', $ticket);
        }

        $waitingBefore = Ticket::where('service_id', $ticket->service_id)
            ->where('ticket_date', $ticket->ticket_date)
            ->where('status', 'waiting')
            ->where('sequence_no', '<', $ticket->sequence_no)
            ->count();

        return view('kiosk.ticket', compact('ticket', 'waitingBefore'));
    }
}
