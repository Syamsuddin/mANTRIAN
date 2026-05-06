<x-layouts.app title="Assignment">
    <h1 class="mb-4 text-2xl font-semibold brand">Assignment Operator</h1>
    <form class="mb-5 grid gap-3 md:grid-cols-3" method="post" action="{{ route('admin.assignments.store') }}">@csrf
        <div class="card p-4 rounded-lg">
            <select class="rounded border px-3 py-2" name="user_id" required><option value="">Operator</option>@foreach($operators as $operator)<option value="{{ $operator->id }}">{{ $operator->name }}</option>@endforeach</select>
        </div>
        <div class="card p-4 rounded-lg">
            <select class="rounded border px-3 py-2" name="counter_id" required><option value="">Loket</option>@foreach($counters as $counter)<option value="{{ $counter->id }}">{{ $counter->name }}</option>@endforeach</select>
        </div>
        <div class="card p-4 rounded-lg flex items-center justify-end">
            <button class="btn-primary">Aktifkan</button>
        </div>
    </form>
    <div class="overflow-hidden rounded-lg border bg-white"><table class="w-full text-left text-sm"><thead class="bg-slate-100"><tr><th class="p-3">Operator</th><th>Loket</th><th>Status</th><th></th></tr></thead><tbody>@foreach($assignments as $assignment)<tr class="border-t"><td class="p-3">{{ $assignment->user->name }}</td><td>{{ $assignment->counter->name }}</td><td>{{ $assignment->is_active ? 'Aktif' : 'Berakhir' }}</td><td>@if($assignment->is_active)<form method="post" action="{{ route('admin.assignments.end', $assignment) }}">@csrf @method('patch')<button class="text-red-700">Akhiri</button></form>@endif</td></tr>@endforeach</tbody></table></div>
</x-layouts.app>
