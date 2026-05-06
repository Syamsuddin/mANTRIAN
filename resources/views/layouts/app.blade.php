<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'mANTRIAN' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="theme-light-blue bg-slate-50 text-slate-900 antialiased">
    <div class="min-h-screen md:flex">
        @auth
            <aside class="w-full sidebar border-b border-slate-200 bg-white md:min-h-screen md:w-64 md:border-b-0 md:border-r">
                <div class="px-5 py-4">
                    <div class="text-lg brand">mANTRIAN</div>
                    <div class="text-xs user-meta">{{ auth()->user()->name }} - {{ auth()->user()->role }}</div>
                </div>
                <nav class="grid gap-1 px-3 pb-4 text-sm">
                    @if(auth()->user()->role !== 'operator')
                        <a class="rounded px-3 py-2 hover:bg-slate-100" href="{{ route('admin.dashboard') }}">Dashboard</a>
                        @if(auth()->user()->isAdminLike())
                            <a class="rounded px-3 py-2 hover:bg-slate-100" href="{{ route('admin.services.index') }}">Layanan</a>
                            <a class="rounded px-3 py-2 hover:bg-slate-100" href="{{ route('admin.counters.index') }}">Loket</a>
                            <a class="rounded px-3 py-2 hover:bg-slate-100" href="{{ route('admin.assignments.index') }}">Assignment</a>
                            <a class="rounded px-3 py-2 hover:bg-slate-100" href="{{ route('admin.audit.index') }}">Audit</a>
                        @endif
                        <a class="rounded px-3 py-2 hover:bg-slate-100" href="{{ route('admin.reports.queues') }}">Laporan</a>
                    @else
                        <a class="rounded px-3 py-2 hover:bg-slate-100" href="{{ route('operator.index') }}">Console Operator</a>
                    @endif
                    <a class="rounded px-3 py-2 hover:bg-slate-100" href="{{ route('kiosk.index') }}">Kiosk</a>
                    <a class="rounded px-3 py-2 hover:bg-slate-100" href="{{ route('display.board') }}">Display</a>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full rounded px-3 py-2 text-left text-red-700 hover:bg-red-50">Logout</button>
                    </form>
                </nav>
            </aside>
        @endauth
        <main class="flex-1 p-4 md:p-8">
            @if(session('status'))
                <div class="mb-4 alert-success">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 alert-danger">{{ $errors->first() }}</div>
            @endif
            <div class="card">{{ $slot }}</div>
        </main>
    </div>
</body>
</html>
