<?php

use App\Http\Controllers\DeviceTypeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
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

    Route::controller(DeviceTypeController::class)->prefix('device-types')->name('device-types.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{deviceType}/edit', 'edit')->name('edit');
        Route::put('/{deviceType}', 'update')->name('update');
        Route::delete('/{deviceType}', 'destroy')->name('destroy');
    });

    Route::controller(ServiceController::class)->prefix('services')->name('services.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{service}/edit', 'edit')->name('edit');
        Route::put('/{service}', 'update')->name('update');
        Route::delete('/{service}', 'destroy')->name('destroy');
    });

    Route::controller(InvoiceController::class)->prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{invoice}', 'show')->name('show');
        Route::post('/{invoice}/items', 'addItem')->name('items.store');
        Route::delete('/{invoice}/items/{item}', 'removeItem')->name('items.destroy');
        Route::patch('/{invoice}/technician-notes', 'updateTechnicianNotes')->name('technician-notes');
        Route::patch('/{invoice}/status', 'updateStatus')->name('update-status');
        Route::get('/{invoice}/print-receipt', 'printReceipt')->name('print-receipt');
        Route::get('/{invoice}/print-invoice', 'printInvoice')->name('print-invoice');
    });

    Route::get('/device-types/{deviceType}/services', [ServiceController::class, 'byDeviceType'])->name('device-types.services');

    Route::get('/warranty-checks', function () {
        return view('transactions.warranty-check.index');
    })->name('warranty-checks');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
