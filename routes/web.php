<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FailedJobsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $number = config('whatsapp-agent.number');

    return view('welcome', [
        'whatsappInviteUrl' => $number ? 'https://wa.me/'.$number : null,
    ]);
});

Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'create'])->name('login');
        Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:6,1');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

        Route::get('/failed-jobs', [FailedJobsController::class, 'index'])->name('failed-jobs.index');
        Route::post('/failed-jobs/{uuid}/retry', [FailedJobsController::class, 'retry'])->name('failed-jobs.retry');
        Route::post('/failed-jobs/flush', [FailedJobsController::class, 'flush'])->name('failed-jobs.flush');
    });
});
