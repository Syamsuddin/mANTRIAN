<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index()
    {
        return view('admin.services.index', ['services' => Service::orderBy('sort_order')->paginate(20)]);
    }

    public function create()
    {
        return view('admin.services.form', ['service' => new Service()]);
    }

    public function store(Request $request, AuditLogger $audit)
    {
        $service = Service::create($this->validated($request));
        $audit->log('service.created', $service, metadata: $service->toArray(), request: $request);

        return redirect()->route('admin.services.index')->with('status', 'Layanan dibuat.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', compact('service'));
    }

    public function update(Request $request, Service $service, AuditLogger $audit)
    {
        $before = $service->toArray();
        $service->update($this->validated($request, $service));
        $audit->log('service.updated', $service, metadata: ['before' => $before, 'after' => $service->toArray()], request: $request);

        return redirect()->route('admin.services.index')->with('status', 'Layanan diperbarui.');
    }

    private function validated(Request $request, ?Service $service = null): array
    {
        return $request->validate([
            'code' => ['required', 'max:30', Rule::unique('services')->ignore($service)],
            'name' => ['required', 'max:120'],
            'description' => ['nullable', 'max:1000'],
            'prefix' => ['required', 'max:5', Rule::unique('services')->ignore($service)],
            'color' => ['nullable', 'max:20'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => false];
    }
}
