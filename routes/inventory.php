<?php

use App\Http\Controllers\Inventory\{
    ProductController, CategoryController, SupplierController,
    CustomerController, PurchaseController, SaleController,
    StockController, InvoiceController
};
use Illuminate\Support\Facades\Route;

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
Route::resource('suppliers', SupplierController::class);
Route::resource('customers', CustomerController::class);
Route::resource('purchases', PurchaseController::class);
Route::resource('sales', SaleController::class);
Route::get('invoices/{sale}/download', [InvoiceController::class, 'download'])->name('invoices.download');
Route::get('reports', [StockController::class, 'report'])->name('reports.index');
