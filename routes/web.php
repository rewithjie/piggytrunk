<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\RaiserController;
use App\Http\Controllers\RetailController;
use App\Http\Controllers\SettingsController;
use App\Support\AdminAsset;
use Illuminate\Support\Facades\Route;

Route::get('/assets/admin.css', fn () => AdminAsset::css())->name('assets.admin.css');
Route::get('/assets/admin.js', fn () => AdminAsset::js())->name('assets.admin.js');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/raisers', [RaiserController::class, 'index'])->name('raisers.index');
Route::get('/raisers/create', [RaiserController::class, 'create'])->name('raisers.create');
Route::post('/raisers', [RaiserController::class, 'store'])->name('raisers.store');
Route::get('/raisers/{raiser}', [RaiserController::class, 'show'])->name('raisers.show');
Route::get('/raisers/{raiser}/edit', [RaiserController::class, 'edit'])->name('raisers.edit');
Route::put('/raisers/{raiser}', [RaiserController::class, 'update'])->name('raisers.update');

Route::get('/investment', [InvestmentController::class, 'index'])->name('investment.index');

Route::get('/retail-shop', [RetailController::class, 'index'])->name('retail.index');

Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');

Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
