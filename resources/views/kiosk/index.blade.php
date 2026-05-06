<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kiosk mANTRIAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-950">
    <main class="mx-auto max-w-5xl p-5 md:p-10">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold brand">Pilih Layanan</h1>
                <p class="mt-1 user-meta">{{ now()->translatedFormat('l, d F Y H:i') }}</p>
            </div>
            <a class="btn-primary" href="{{ route('login') }}">Login</a>
        </div>
        @if($errors->any())
            <div class="mb-4 alert-danger">{{ $errors->first() }}</div>
        @endif
        <div class="grid gap-4 sm:grid-cols-2">
            @forelse($services as $service)
                <form method="post" action="{{ route('kiosk.tickets.store') }}">
                    @csrf
                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                    <button class="min-h-36 w-full card p-6 text-left transition hover:shadow-lg focus:outline-none">
                        <span class="block text-4xl font-black">{{ $service->prefix }}</span>
                        <span class="mt-3 block text-2xl font-semibold">{{ $service->name }}</span>
                        @if($service->description)<span class="mt-2 user-meta">{{ $service->description }}</span>@endif
                    </button>
                </form>
            @empty
                <div class="card">Belum ada layanan aktif.</div>
            @endforelse
        </div>
    </main>
</body>
</html>
