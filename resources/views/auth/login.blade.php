<x-layouts.app title="Login mANTRIAN">
    <div class="mx-auto mt-12 max-w-md">
        <div class="card p-6">
            <h1 class="text-2xl font-semibold brand">Login</h1>
            <form class="mt-6 grid gap-4" method="post" action="{{ route('login') }}">
                @csrf
                <label class="grid gap-1 text-sm font-medium">Email
                    <input class="rounded border border-slate-300 px-3 py-2" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </label>
                <label class="grid gap-1 text-sm font-medium">Password
                    <input class="rounded border border-slate-300 px-3 py-2" type="password" name="password" required>
                </label>
                <button class="btn-primary">Masuk</button>
            </form>
        </div>
    </div>
</x-layouts.app>
