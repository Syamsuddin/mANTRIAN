<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\QueueReportService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request, QueueReportService $reports)
    {
        $date = $request->query('date', now()->toDateString());

        return view('admin.dashboard', [
            'date' => $date,
            'summary' => $reports->summary($date),
            'byService' => $reports->byService($date),
        ]);
    }
}
