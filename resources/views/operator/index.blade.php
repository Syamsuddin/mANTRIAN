<x-layouts.app title="Operator">
    <div class="mb-5">
        <h1 class="text-2xl font-semibold brand">Console Operator</h1>
        <p class="user-meta">{{ $counter ? $counter->name : 'Belum ada assignment aktif' }}</p>
    </div>
    @if($counter)
        <div class="grid gap-5 lg:grid-cols-[1fr_360px]">
            <section class="card">
                <div class="text-sm user-meta">Nomor Aktif</div>
                <div class="mt-3 min-h-36 rounded-lg bg-slate-950 p-6 text-white">
                    @if($active)
                        <div class="text-7xl font-black">{{ $active->ticket_no }}</div>
                        <div class="mt-2 text-xl">{{ $active->service_name_snapshot }}</div>
                    @else
                        <div class="pt-10 text-center text-slate-300">Tidak ada nomor aktif</div>
                    @endif
                </div>
                <div class="mt-5 grid gap-3 sm:grid-cols-4">
                    <form method="post" action="{{ route('operator.queue.call-next') }}">@csrf<input type="hidden" name="counter_id" value="{{ $counter->id }}"><button class="w-full btn-primary">Panggil</button></form>
                    @if($active)
                        <form method="post" action="{{ route('operator.queue.recall', $active) }}">@csrf<input type="hidden" name="counter_id" value="{{ $counter->id }}"><button class="w-full rounded bg-slate-700 px-4 py-3 font-semibold text-white">Ulang</button></form>
                        <form method="post" action="{{ route('operator.queue.skip', $active) }}">@csrf<input type="hidden" name="counter_id" value="{{ $counter->id }}"><button class="w-full rounded bg-amber-600 px-4 py-3 font-semibold text-white">Skip</button></form>
                        <form method="post" action="{{ route('operator.queue.done', $active) }}">@csrf<input type="hidden" name="counter_id" value="{{ $counter->id }}"><button class="w-full rounded bg-emerald-700 px-4 py-3 font-semibold text-white">Selesai</button></form>
                    @endif
                </div>
            </section>
            <aside class="card">
                <h2 class="font-semibold">Menunggu</h2>
                <div class="mt-3 grid gap-2">
                    @forelse($waiting as $ticket)
                        <div class="flex items-center justify-between rounded border px-3 py-2"><span class="font-semibold">{{ $ticket->ticket_no }}</span><span class="text-sm user-meta">{{ $ticket->created_at->diffForHumans() }}</span></div>
                    @empty
                        <div class="rounded border border-dashed p-4 text-sm user-meta">Belum ada antrian menunggu.</div>
                    @endforelse
                </div>
            </aside>
        </div>
    @else
        <div class="rounded-lg border bg-white p-6 text-slate-600">Hubungi admin untuk mengaktifkan assignment loket.</div>
    @endif
</x-layouts.app>
