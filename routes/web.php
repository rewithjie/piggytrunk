<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\RaiserController;
use App\Http\Controllers\RetailController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DatabaseReportController;
use App\Http\Controllers\Api\StockEntryController;
use App\Support\AdminAsset;
use Illuminate\Support\Facades\Route;

Route::get('/assets/admin.css', fn () => AdminAsset::css())->name('assets.admin.css');
Route::get('/assets/admin.js', fn () => AdminAsset::js())->name('assets.admin.js');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login.form');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
});

Route::middleware('admin.auth')->group(function () {
    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/raisers/{raiser}/download-report', [DashboardController::class, 'downloadReport'])->name('raisers.download-report');

    Route::get('/raisers', [RaiserController::class, 'index'])->name('raisers.index');
    Route::get('/raisers/create', [RaiserController::class, 'create'])->name('raisers.create');
    Route::post('/raisers', [RaiserController::class, 'store'])->name('raisers.store');
    Route::get('/raisers/{raiser}', [RaiserController::class, 'show'])->name('raisers.show');
    Route::get('/raisers/{raiser}/edit', [RaiserController::class, 'edit'])->name('raisers.edit');
    Route::put('/raisers/{raiser}', [RaiserController::class, 'update'])->name('raisers.update');

    Route::get('/investment', [InvestmentController::class, 'index'])->name('investments.index');
    Route::get('/investment/create', [InvestmentController::class, 'create'])->name('investments.create');
    Route::post('/investment', [InvestmentController::class, 'store'])->name('investments.store');

    Route::get('/retail-shop', [RetailController::class, 'index'])->name('retail.index');
    Route::get('/retail-shop/products/create', [RetailController::class, 'createProduct'])->name('retail.products.create');
    Route::post('/retail-shop/products', [RetailController::class, 'storeProduct'])->name('retail.products.store');
    Route::get('/retail-shop/products/{product}/edit', [RetailController::class, 'editProduct'])->name('retail.products.edit');
    Route::put('/retail-shop/products/{product}', [RetailController::class, 'updateProduct'])->name('retail.products.update');
    Route::delete('/retail-shop/products/{product}', [RetailController::class, 'destroyProduct'])->name('retail.products.destroy');
    Route::get('/retail-shop/transactions/create', [RetailController::class, 'createTransaction'])->name('retail.transactions.create');
    Route::post('/retail-shop/transactions', [RetailController::class, 'storeTransaction'])->name('retail.transactions.store');
    Route::get('/retail-shop/transactions/{transaction}/edit', [RetailController::class, 'editTransaction'])->name('retail.transactions.edit');
    Route::put('/retail-shop/transactions/{transaction}', [RetailController::class, 'updateTransaction'])->name('retail.transactions.update');
    Route::delete('/retail-shop/transactions/{transaction}', [RetailController::class, 'destroyTransaction'])->name('retail.transactions.destroy');

    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');

    // API Routes for Stock Management
    Route::post('/api/stock-entry', [StockEntryController::class, 'store'])->name('api.stock-entry.store');

    Route::get('/database-report', [DatabaseReportController::class, 'show'])->name('database.report');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
});
