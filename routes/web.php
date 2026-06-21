<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\ProductManager;
use App\Livewire\Admin\MachineManager;
use App\Livewire\Admin\OrderMonitor;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/machine/{unique_code}', App\Livewire\Storefront::class)->name('storefront');

Route::prefix('admin')->group(function () {
    Route::get('/', Dashboard::class);
    Route::get('/products', ProductManager::class);
    Route::get('/machines', MachineManager::class);
    Route::get('/orders', OrderMonitor::class);
});
