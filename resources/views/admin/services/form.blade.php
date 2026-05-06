<x-layouts.app title="Form Layanan">
    <h1 class="mb-4 text-2xl font-semibold brand">{{ $service->exists ? 'Edit' : 'Tambah' }} Layanan</h1>
    <form class="grid max-w-2xl gap-4" method="post" action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}">
        @csrf @if($service->exists) @method('put') @endif
        <div class="card p-5 grid gap-4 rounded-lg border bg-white">
            <input class="rounded border px-3 py-2" name="code" placeholder="Kode" value="{{ old('code', $service->code) }}" required>
            <input class="rounded border px-3 py-2" name="name" placeholder="Nama layanan" value="{{ old('name', $service->name) }}" required>
            <input class="rounded border px-3 py-2" name="prefix" placeholder="Prefix tiket" value="{{ old('prefix', $service->prefix) }}" required>
            <textarea class="rounded border px-3 py-2" name="description" placeholder="Deskripsi">{{ old('description', $service->description) }}</textarea>
            <input class="rounded border px-3 py-2" name="color" placeholder="Warna/hex" value="{{ old('color', $service->color) }}">
            <input class="rounded border px-3 py-2" type="number" name="sort_order" value="{{ old('sort_order', $service->sort_order ?? 0) }}" required>
            <label class="flex gap-2 text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service->is_active ?? true))> Aktif</label>
            <div class="flex justify-end"><button class="btn-primary">Simpan</button></div>
        </div>
    </form>
</x-layouts.app>
