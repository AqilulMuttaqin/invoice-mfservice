<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::match(['get', 'head'], '/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    } else if (Auth::user()) {
        return redirect()->route('dashboard');
    }
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/device-types', function () {
        return view('master-data.device-types.index');
    })->name('device-types');

    Route::get('/services', function () {
        return view('master-data.services.index');
    })->name('services');

    Route::get('/invoices', function () {
        return view('transactions.invoices.index');
    })->name('invoices');

    Route::get('/warranty-checks', function () {
        return view('transactions.warranty-check.index');
    })->name('warranty-checks');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
