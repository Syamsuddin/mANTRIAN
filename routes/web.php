<?php

use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\Operator\ConsoleController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/kiosk');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/kiosk', [KioskController::class, 'index'])->name('kiosk.index');
Route::post('/kiosk/tickets', [KioskController::class, 'store'])->middleware('throttle:20,1')->name('kiosk.tickets.store');
Route::get('/kiosk/tickets/{ticket}', [KioskController::class, 'show'])->name('kiosk.tickets.show');

Route::get('/display', [DisplayController::class, 'board'])->name('display.board');
Route::get('/api/display/state', [DisplayController::class, 'state'])->middleware('throttle:120,1')->name('display.state');

Route::middleware(['auth', 'role:super_admin,admin,supervisor'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/reports/queues', [ReportController::class, 'queues'])->name('reports.queues');

    Route::middleware('role:super_admin,admin')->group(function () {
        Route::resource('services', ServiceController::class)->except(['show', 'destroy']);
        Route::resource('counters', CounterController::class)->except(['show', 'destroy']);
        Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
        Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
        Route::patch('/assignments/{assignment}/end', [AssignmentController::class, 'end'])->name('assignments.end');
        Route::get('/audit-logs', [ReportController::class, 'audit'])->name('audit.index');
    });
});

Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/', [ConsoleController::class, 'index'])->name('index');
    Route::post('/queue/call-next', [ConsoleController::class, 'callNext'])->name('queue.call-next');
    Route::post('/queue/{ticket}/recall', [ConsoleController::class, 'recall'])->name('queue.recall');
    Route::post('/queue/{ticket}/skip', [ConsoleController::class, 'skip'])->name('queue.skip');
    Route::post('/queue/{ticket}/done', [ConsoleController::class, 'done'])->name('queue.done');
});
