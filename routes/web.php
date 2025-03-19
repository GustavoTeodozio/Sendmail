<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Mail\Send;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\EmailController;
use App\Livewire\SmtpSettings;
use GuzzleHttp\Middleware;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');



Route::delete('/dashboard/delete/{id}', [DashboardController::class, 'destroy'])->name('dashboard.delete');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/email', [EmailController::class, 'index'])->name('email.form');
    Route::post('/enviar-email', [EmailController::class, 'send'])->name('enviar.email');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/smtp-settings', SmtpSettings::class)->name('smtp.settings');
});

require __DIR__ . '/auth.php';
