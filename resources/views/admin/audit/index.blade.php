<x-layouts.app title="Audit">
    <h1 class="mb-4 text-2xl font-semibold">Audit Log</h1>
    <div class="overflow-hidden rounded-lg border bg-white"><table class="w-full text-left text-sm"><thead class="bg-slate-100"><tr><th class="p-3">Waktu</th><th>Aktor</th><th>Aksi</th><th>Entity</th></tr></thead><tbody>@foreach($logs as $log)<tr class="border-t"><td class="p-3">{{ $log->created_at }}</td><td>{{ $log->actor?->name ?? 'System' }}</td><td>{{ $log->action }}</td><td>{{ $log->entity_type }} #{{ $log->entity_id }}</td></tr>@endforeach</tbody></table></div>
</x-layouts.app>
