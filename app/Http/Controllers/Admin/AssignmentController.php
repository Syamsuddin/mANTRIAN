<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\CounterAssignment;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        return view('admin.assignments.index', [
            'assignments' => CounterAssignment::with('user', 'counter')->latest()->paginate(20),
            'operators' => User::where('role', 'operator')->where('is_active', true)->orderBy('name')->get(),
            'counters' => Counter::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, AuditLogger $audit)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'counter_id' => ['required', 'exists:counters,id'],
        ]);

        CounterAssignment::where('user_id', $data['user_id'])->where('is_active', true)->update([
            'is_active' => false,
            'end_at' => now(),
        ]);

        $assignment = CounterAssignment::create($data + ['start_at' => now(), 'is_active' => true]);
        $audit->log('assignment.created', $assignment, metadata: $data, request: $request);

        return back()->with('status', 'Assignment aktif diperbarui.');
    }

    public function end(CounterAssignment $assignment, AuditLogger $audit)
    {
        $assignment->update(['is_active' => false, 'end_at' => now()]);
        $audit->log('assignment.ended', $assignment);

        return back()->with('status', 'Assignment diakhiri.');
    }
}
