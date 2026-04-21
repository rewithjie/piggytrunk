<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CashierAuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\RaiserController;
use App\Http\Controllers\RetailController;
use App\Http\Controllers\QuickSaleController;
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
    Route::get('/investment/{raiser}', [InvestmentController::class, 'show'])->name('investments.show');

    Route::get('/pos', [RetailController::class, 'index'])->name('retail.index');
    Route::get('/pos/archives', [RetailController::class, 'archiveIndex'])->name('retail.archives');
    Route::put('/pos/products/{product}/restore', [RetailController::class, 'restoreProduct'])->name('retail.products.restore');
    Route::get('/pos/products/create', [RetailController::class, 'createProduct'])->name('retail.products.create');
    Route::post('/pos/products', [RetailController::class, 'storeProduct'])->name('retail.products.store');
    Route::get('/pos/products/{product}/edit', [RetailController::class, 'editProduct'])->name('retail.products.edit');
    Route::put('/pos/products/{product}', [RetailController::class, 'updateProduct'])->name('retail.products.update');
    Route::delete('/pos/products/{product}', [RetailController::class, 'destroyProduct'])->name('retail.products.destroy');
    Route::get('/pos/transactions/create', [RetailController::class, 'createTransaction'])->name('retail.transactions.create');
    Route::post('/pos/transactions', [RetailController::class, 'storeTransaction'])->name('retail.transactions.store');
    Route::get('/pos/transactions/{transaction}/edit', [RetailController::class, 'editTransaction'])->name('retail.transactions.edit');
    Route::put('/pos/transactions/{transaction}', [RetailController::class, 'updateTransaction'])->name('retail.transactions.update');
    Route::delete('/pos/transactions/{transaction}', [RetailController::class, 'destroyTransaction'])->name('retail.transactions.destroy');

    // Quick Sale Routes
    Route::get('/api/quick-sale/session', [QuickSaleController::class, 'getSession'])->name('quick-sale.session');
    Route::post('/api/quick-sale/add-item', [QuickSaleController::class, 'addItem'])->name('quick-sale.add-item');
    Route::put('/api/quick-sale/item/{item}', [QuickSaleController::class, 'updateItem'])->name('quick-sale.update-item');
    Route::delete('/api/quick-sale/item/{item}', [QuickSaleController::class, 'removeItem'])->name('quick-sale.remove-item');
    Route::put('/api/quick-sale/item/{item}/discount', [QuickSaleController::class, 'updateDiscount'])->name('quick-sale.update-discount');
    Route::put('/api/quick-sale/session', [QuickSaleController::class, 'updateSession'])->name('quick-sale.update-session');
    Route::post('/api/quick-sale/confirm', [QuickSaleController::class, 'confirm'])->name('quick-sale.confirm');
    Route::post('/api/quick-sale/cancel', [QuickSaleController::class, 'cancel'])->name('quick-sale.cancel');
    Route::post('/api/quick-sale/clear', [QuickSaleController::class, 'clear'])->name('quick-sale.clear');

    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');

    // API Routes for Stock Management
    Route::post('/api/stock-entry', [StockEntryController::class, 'store'])->name('api.stock-entry.store');

    Route::get('/database-report', [DatabaseReportController::class, 'show'])->name('database.report');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
});

// Cashier Login Routes
Route::get('/cashier/login', [CashierAuthController::class, 'showLogin'])->name('cashier.login.form');
Route::post('/cashier/login', [CashierAuthController::class, 'login'])->name('cashier.login');

// Cashier Routes
Route::middleware('cashier.auth')->group(function () {
    Route::post('/cashier/logout', [CashierAuthController::class, 'logout'])->name('cashier.logout');

    Route::get('/cashier/retail', [CashierController::class, 'retailIndex'])->name('cashier.retail');
    Route::get('/cashier/inventory', [CashierController::class, 'inventoryIndex'])->name('cashier.inventory');
    Route::get('/cashier/retail/archives', [CashierController::class, 'archiveIndex'])->name('cashier.retail.archives');
    Route::put('/cashier/retail/products/{product}/restore', [CashierController::class, 'restoreProduct'])->name('cashier.retail.products.restore');
    Route::get('/cashier/retail/products/create', [CashierController::class, 'createProduct'])->name('cashier.retail.products.create');
    Route::post('/cashier/retail/products', [CashierController::class, 'storeProduct'])->name('cashier.retail.products.store');
    Route::delete('/cashier/retail/products/{product}', [CashierController::class, 'destroyProduct'])->name('cashier.retail.products.destroy');
    
    // Cashier API Routes for Real-time Product Updates
    Route::get('/api/cashier/retail-products', [CashierController::class, 'getRetailProducts'])->name('cashier.api.retail-products');
    Route::get('/api/cashier/inventory-items', [CashierController::class, 'getInventoryItems'])->name('cashier.api.inventory-items');
    
    // Quick Sale API Routes for Cashiers
    Route::get('/api/cashier/quick-sale/session', [QuickSaleController::class, 'getSession'])->name('cashier.quick-sale.session');
    Route::post('/api/cashier/quick-sale/add-item', [QuickSaleController::class, 'addItem'])->name('cashier.quick-sale.add-item');
    Route::put('/api/cashier/quick-sale/item/{item}', [QuickSaleController::class, 'updateItem'])->name('cashier.quick-sale.update-item');
    Route::delete('/api/cashier/quick-sale/item/{item}', [QuickSaleController::class, 'removeItem'])->name('cashier.quick-sale.remove-item');
    Route::put('/api/cashier/quick-sale/item/{item}/discount', [QuickSaleController::class, 'updateDiscount'])->name('cashier.quick-sale.update-discount');
    Route::put('/api/cashier/quick-sale/session', [QuickSaleController::class, 'updateSession'])->name('cashier.quick-sale.update-session');
    Route::post('/api/cashier/quick-sale/confirm', [QuickSaleController::class, 'confirm'])->name('cashier.quick-sale.confirm');
    Route::post('/api/cashier/quick-sale/cancel', [QuickSaleController::class, 'cancel'])->name('cashier.quick-sale.cancel');
    Route::post('/api/cashier/quick-sale/clear', [QuickSaleController::class, 'clear'])->name('cashier.quick-sale.clear');
});
