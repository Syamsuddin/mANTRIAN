<x-layouts.app title="Laporan">
    <h1 class="mb-4 text-2xl font-semibold brand">Laporan Antrian</h1>
    <form class="mb-4 flex items-center gap-2"><input class="rounded border px-3 py-2" type="date" name="date" value="{{ $date }}"><button class="btn-primary">Filter</button></form>
    <div class="overflow-hidden rounded-lg border bg-white"><table class="w-full text-left text-sm"><thead class="bg-slate-100"><tr><th class="p-3">Nomor</th><th>Layanan</th><th>Status</th><th>Ambil</th><th>Panggil</th><th>Selesai</th></tr></thead><tbody>@foreach($tickets as $ticket)<tr class="border-t"><td class="p-3 font-semibold">{{ $ticket->ticket_no }}</td><td>{{ $ticket->service_name_snapshot }}</td><td>{{ $ticket->status }}</td><td>{{ $ticket->created_at?->format('H:i') }}</td><td>{{ $ticket->called_at?->format('H:i') }}</td><td>{{ $ticket->completed_at?->format('H:i') }}</td></tr>@endforeach</tbody></table></div>
</x-layouts.app>
