<?php

namespace App\Http\Controllers\Operator;

use App\Actions\Queue\CallNextTicketAction;
use App\Actions\Queue\CompleteTicketAction;
use App\Actions\Queue\RecallTicketAction;
use App\Actions\Queue\SkipTicketAction;
use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\QueueCall;
use App\Models\Ticket;
use Illuminate\Http\Request;
use RuntimeException;

class ConsoleController extends Controller
{
    public function index(Request $request)
    {
        $assignment = $request->user()->activeAssignments()->with('counter.services')->first();
        $counter = $assignment?->counter;
        $serviceIds = $counter?->services->pluck('id') ?? collect();
        $active = $counter ? QueueCall::with('ticket')
            ->where('counter_id', $counter->id)
            ->whereHas('ticket', fn ($query) => $query->whereIn('status', Ticket::ACTIVE_STATUSES))
            ->latest('called_at')
            ->first()?->ticket : null;

        return view('operator.index', [
            'counter' => $counter,
            'active' => $active,
            'waiting' => Ticket::where('status', 'waiting')->whereIn('service_id', $serviceIds)->orderBy('created_at')->limit(20)->get(),
        ]);
    }

    public function callNext(Request $request, CallNextTicketAction $action)
    {
        $counter = Counter::findOrFail($request->validate(['counter_id' => ['required', 'exists:counters,id']])['counter_id']);

        try {
            $action->execute($request->user(), $counter);
        } catch (RuntimeException $exception) {
            return back()->withErrors(['queue' => $exception->getMessage()]);
        }

        return back()->with('status', 'Nomor berikutnya dipanggil.');
    }

    public function recall(Request $request, Ticket $ticket, RecallTicketAction $action)
    {
        $counter = Counter::findOrFail($request->validate(['counter_id' => ['required', 'exists:counters,id']])['counter_id']);
        $action->execute($request->user(), $counter, $ticket);

        return back()->with('status', 'Panggilan diulang.');
    }

    public function skip(Request $request, Ticket $ticket, SkipTicketAction $action)
    {
        $data = $request->validate(['counter_id' => ['required', 'exists:counters,id'], 'reason' => ['nullable', 'max:255']]);
        $action->execute($request->user(), Counter::findOrFail($data['counter_id']), $ticket, $data['reason'] ?? null);

        return back()->with('status', 'Tiket dilewati.');
    }

    public function done(Request $request, Ticket $ticket, CompleteTicketAction $action)
    {
        $data = $request->validate(['counter_id' => ['required', 'exists:counters,id'], 'notes' => ['nullable', 'max:255']]);
        $action->execute($request->user(), Counter::findOrFail($data['counter_id']), $ticket, $data['notes'] ?? null);

        return back()->with('status', 'Layanan selesai.');
    }
}
