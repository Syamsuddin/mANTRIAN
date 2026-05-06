<x-layouts.app title="Dashboard">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-semibold brand">Dashboard Hari Ini</h1>
        <form class="flex items-center gap-2"><input class="rounded border px-3 py-2" type="date" name="date" value="{{ $date }}"><button class="btn-primary">Filter</button></form>
    </div>
    <div class="grid gap-4 md:grid-cols-5">
        @foreach(['waiting' => 'Menunggu', 'serving' => 'Dilayani', 'done' => 'Selesai', 'skipped' => 'Skip', 'cancelled' => 'Batal'] as $key => $label)
            <div class="card">
                <div class="text-sm user-meta">{{ $label }}</div>
                <div class="mt-2 text-3xl font-bold">{{ $summary[$key] ?? 0 }}</div>
            </div>
        @endforeach
    </div>
    <div class="mt-6 card">
        <h2 class="font-semibold">Rekap per Layanan</h2>
        <div class="overflow-auto">
            <table class="mt-3 w-full text-left text-sm">
                <thead><tr class="border-b"><th class="py-2">Layanan</th><th>Status</th><th>Total</th></tr></thead>
                <tbody>@forelse($byService as $row)<tr class="border-b"><td class="py-2">{{ $row->service_name_snapshot }}</td><td>{{ $row->status }}</td><td>{{ $row->total }}</td></tr>@empty<tr><td class="py-4 user-meta" colspan="3">Belum ada data.</td></tr>@endforelse</tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
