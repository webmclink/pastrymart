<?php

use App\Livewire\HomePage;
use App\Livewire\ListInvoices;
use App\Livewire\InvoiceDetail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogoutController;

Route::middleware('guest')->group(function () {
    Route::get('/', HomePage::class)->name('login');
});

Route::middleware(['auth', 'localization'])->group(function () {
    Route::prefix('invoices')->group(function () {
        Route::get('/', ListInvoices::class)->name('invoices')->lazy();
    
        Route::get('/{invoiceId}', InvoiceDetail::class)->name('invoice.detail')->lazy();
    });

    Route::post('/logout', LogoutController::class)->name('logout');
});
