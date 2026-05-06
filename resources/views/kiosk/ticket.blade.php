<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tiket {{ $ticket->ticket_no }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            @page { size: 80mm auto; margin: 4mm; }
            .no-print { display: none !important; }
            body { background: white !important; }
            .ticket { box-shadow: none !important; border: 0 !important; width: 72mm; }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 p-5 text-slate-950" onload="setTimeout(() => window.print(), 300)">
    <main class="mx-auto max-w-sm">
        <div class="ticket card text-center">
            <div class="text-sm font-semibold brand">mANTRIAN</div>
            <div class="mt-4 text-6xl font-black tracking-normal">{{ $ticket->ticket_no }}</div>
            <div class="mt-3 text-xl font-semibold">{{ $ticket->service_name_snapshot }}</div>
            <div class="mt-4 text-sm user-meta">{{ $ticket->created_at->format('d/m/Y H:i') }}</div>
            <div class="mt-2 text-sm">Antrian sebelum Anda: {{ $waitingBefore }}</div>
        </div>
        <div class="no-print mt-5 grid gap-3">
            <button class="btn-primary" onclick="window.print()">Cetak Ulang</button>
            <a class="rounded border border-slate-300 bg-white px-4 py-3 text-center font-semibold" href="{{ route('kiosk.index') }}">Kembali</a>
        </div>
    </main>
</body>
</html>
