<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'now' => now(),
        'events' => \App\Models\Event::all(),
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/calendar', function () {
    return Inertia::render('Calendar');
})->middleware(['auth', 'verified'])->name('calendar');
Route::get('/calendar/import', [CalendarController::class, 'show'])->middleware(['auth', 'verified'])->name('calendar.import');
Route::post('/calendar/import', [CalendarController::class, 'store'])->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('loudness', [App\Http\Controllers\LoudnessController::class, 'show'])->name('loudness.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('events/countdowns', [App\Http\Controllers\EventController::class, 'countdowns'])->name('events.countdowns');
    Route::get('events/trips', [App\Http\Controllers\EventController::class, 'trips'])->name('events.trips');
    Route::resource('events', App\Http\Controllers\EventController::class);
});
