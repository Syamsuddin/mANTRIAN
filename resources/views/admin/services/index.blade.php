<x-layouts.app title="Layanan">
    <div class="mb-4 flex items-center justify-between"><h1 class="text-2xl font-semibold brand">Layanan</h1><a class="btn-primary" href="{{ route('admin.services.create') }}">Tambah</a></div>
    <div class="overflow-hidden rounded-lg border bg-white"><table class="w-full text-left text-sm"><thead class="bg-slate-100"><tr><th class="p-3">Kode</th><th>Nama</th><th>Prefix</th><th>Status</th><th></th></tr></thead><tbody>@foreach($services as $service)<tr class="border-t"><td class="p-3">{{ $service->code }}</td><td>{{ $service->name }}</td><td>{{ $service->prefix }}</td><td>{{ $service->is_active ? 'Aktif' : 'Nonaktif' }}</td><td><a class="text-blue-700" href="{{ route('admin.services.edit', $service) }}">Edit</a></td></tr>@endforeach</tbody></table></div>
    <div class="mt-4">{{ $services->links() }}</div>
</x-layouts.app>
