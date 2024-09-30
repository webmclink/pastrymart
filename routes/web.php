<?php

use App\Livewire\HomePage;
use App\Livewire\ListOrders;
use App\Livewire\OrderDetail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogoutController;

Route::middleware('guest')->group(function () {
    Route::get('/', HomePage::class)->name('login');
});

Route::middleware(['auth', 'localization'])->group(function () {
    Route::prefix('orders')->group(function () {
        Route::get('/', ListOrders::class)->name('orders')->lazy();
    
        Route::get('/{orderId}', OrderDetail::class)->name('order.detail')->lazy();
    });

    Route::post('/logout', LogoutController::class)->name('logout');
});


Route::get('/test', function () {
    return view('pdf.sales-order');
});