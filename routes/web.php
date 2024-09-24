<?php

use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogoutController;
use App\Livewire\ListInvoices;

Route::middleware('guest')->group(function () {
    Route::get('/', HomePage::class)->name('login');
});

Route::middleware(['auth', 'localization'])->group(function () {
    Route::get('/invoices', ListInvoices::class)->name('invoices');

    Route::post('/logout', LogoutController::class)->name('logout');
});
