<x-layouts.app title="Form Loket">
    <h1 class="mb-4 text-2xl font-semibold brand">{{ $counter->exists ? 'Edit' : 'Tambah' }} Loket</h1>
    <form class="grid max-w-2xl gap-4" method="post" action="{{ $counter->exists ? route('admin.counters.update', $counter) : route('admin.counters.store') }}">
        @csrf @if($counter->exists) @method('put') @endif
        <div class="card p-5 grid gap-4 rounded-lg border bg-white">
            <input class="rounded border px-3 py-2" name="code" placeholder="Kode" value="{{ old('code', $counter->code) }}" required>
            <input class="rounded border px-3 py-2" name="name" placeholder="Nama loket" value="{{ old('name', $counter->name) }}" required>
            <input class="rounded border px-3 py-2" name="location" placeholder="Lokasi" value="{{ old('location', $counter->location) }}">
            <input class="rounded border px-3 py-2" type="number" name="sort_order" value="{{ old('sort_order', $counter->sort_order ?? 0) }}" required>
            <div class="grid gap-2 text-sm">@foreach($services as $service)<label class="flex gap-2"><input type="checkbox" name="service_ids[]" value="{{ $service->id }}" @checked(in_array($service->id, old('service_ids', $counter->services->pluck('id')->all())))> {{ $service->name }}</label>@endforeach</div>
            <label class="flex gap-2 text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $counter->is_active ?? true))> Aktif</label>
            <div class="flex justify-end"><button class="btn-primary">Simpan</button></div>
        </div>
    </form>
</x-layouts.app>
