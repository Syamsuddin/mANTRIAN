<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Display mANTRIAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen text-slate-900">
    <main class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold brand">mANTRIAN</h1>
            <div id="clock" class="text-xl user-meta"></div>
        </div>
        <section class="mb-6 display-board p-6">
            <div class="user-meta">Panggilan Terbaru</div>
            <div id="latest" class="mt-3 text-7xl font-black">{{ $state['latest_call']['ticket_no'] ?? '-' }}</div>
            <div id="latestMeta" class="mt-2 text-3xl user-meta">{{ ($state['latest_call']['counter_name'] ?? '') }} {{ ($state['latest_call']['service_name'] ?? '') }}</div>
        </section>
        <section id="counters" class="grid gap-4 md:grid-cols-3 xl:grid-cols-4">
            @foreach($state['counters'] as $counter)
                <div class="card p-5">
                    <div class="text-xl font-semibold">{{ $counter['name'] }}</div>
                    <div class="mt-4 text-5xl font-black">{{ $counter['current_ticket_no'] ?? '-' }}</div>
                    <div class="mt-2 user-meta">{{ $counter['service_name'] ?? 'Belum melayani' }}</div>
                </div>
            @endforeach
        </section>
    </main>
    <script>
        const render = (state) => {
            document.getElementById('latest').textContent = state.latest_call?.ticket_no ?? '-';
            document.getElementById('latestMeta').textContent = [state.latest_call?.counter_name, state.latest_call?.service_name].filter(Boolean).join(' ');
            document.getElementById('counters').innerHTML = state.counters.map(counter => `
                <div class="rounded-lg border border-slate-700 bg-slate-900 p-5">
                    <div class="text-xl font-semibold">${counter.name}</div>
                    <div class="mt-4 text-5xl font-black">${counter.current_ticket_no ?? '-'}</div>
                    <div class="mt-2 text-slate-300">${counter.service_name ?? 'Belum melayani'}</div>
                </div>
            `).join('');
        };
        setInterval(() => document.getElementById('clock').textContent = new Date().toLocaleString('id-ID'), 1000);
        setInterval(() => fetch('{{ route('display.state') }}').then(r => r.json()).then(render), 3000);
    </script>
</body>
</html>
