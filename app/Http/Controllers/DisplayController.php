<?php

namespace App\Http\Controllers;

use App\Services\DisplayStateService;
use Illuminate\Http\Request;

class DisplayController extends Controller
{
    public function board(DisplayStateService $display)
    {
        return view('display.board', ['state' => $display->state()]);
    }

    public function state(Request $request, DisplayStateService $display)
    {
        return response()->json($display->state($request->query('date')));
    }
}
