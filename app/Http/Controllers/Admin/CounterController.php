<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Service;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CounterController extends Controller
{
    public function index()
    {
        return view('admin.counters.index', ['counters' => Counter::with('services')->orderBy('sort_order')->paginate(20)]);
    }

    public function create()
    {
        return view('admin.counters.form', ['counter' => new Counter(), 'services' => Service::orderBy('name')->get()]);
    }

    public function store(Request $request, AuditLogger $audit)
    {
        $data = $this->validated($request);
        $counter = Counter::create($data);
        $counter->services()->sync($request->input('service_ids', []));
        $audit->log('counter.created', $counter, metadata: $data, request: $request);

        return redirect()->route('admin.counters.index')->with('status', 'Loket dibuat.');
    }

    public function edit(Counter $counter)
    {
        return view('admin.counters.form', ['counter' => $counter, 'services' => Service::orderBy('name')->get()]);
    }

    public function update(Request $request, Counter $counter, AuditLogger $audit)
    {
        $data = $this->validated($request, $counter);
        $counter->update($data);
        $counter->services()->sync($request->input('service_ids', []));
        $audit->log('counter.updated', $counter, metadata: $data, request: $request);

        return redirect()->route('admin.counters.index')->with('status', 'Loket diperbarui.');
    }

    private function validated(Request $request, ?Counter $counter = null): array
    {
        return $request->validate([
            'code' => ['required', 'max:30', Rule::unique('counters')->ignore($counter)],
            'name' => ['required', 'max:120'],
            'location' => ['nullable', 'max:120'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'service_ids' => ['array'],
            'service_ids.*' => ['exists:services,id'],
        ]) + ['is_active' => false];
    }
}
