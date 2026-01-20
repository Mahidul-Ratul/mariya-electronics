<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\InstallmentSaleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Products Routes
Route::resource('products', ProductController::class);
Route::get('/products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
Route::patch('/products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.update-stock');
Route::get('/products-search', [ProductController::class, 'search'])->name('products.search');

// Categories Routes
Route::resource('categories', CategoryController::class);

// Brands Routes
Route::resource('brands', BrandController::class);

// Customers Routes
Route::resource('customers', CustomerController::class);

// Sales Routes
Route::get('/sales/choose-type', [SaleController::class, 'chooseType'])->name('sales.choose-type');
Route::get('/sales/direct-sale', [SaleController::class, 'createDirectSale'])->name('sales.create-direct');
Route::post('/sales/direct-sale', [SaleController::class, 'storeDirectSale'])->name('sales.store-direct');
Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
Route::get('/sales/{sale}/invoice', [SaleController::class, 'invoice'])->name('sales.invoice');
Route::get('/sales/{sale}/invoice/download', [SaleController::class, 'downloadInvoice'])->name('sales.invoice.download');
Route::get('/sales/{sale}/print', [SaleController::class, 'print'])->name('sales.print');
Route::resource('sales', SaleController::class);

// Installment Sales Routes
Route::resource('installment-sales', InstallmentSaleController::class);
Route::get('/installment-sales/{installment_sale}/invoice', [InstallmentSaleController::class, 'invoice'])->name('installment-sales.invoice');
Route::get('/installment-sales/{installment_sale}/invoice/download', [InstallmentSaleController::class, 'downloadInvoice'])->name('installment-sales.invoice.download');

// Product filtering for sales
Route::get('/api/brands/{brand}/models', [SaleController::class, 'getProductsByBrand'])->name('api.brands.models');
Route::get('/api/products/by-brand/{brand}', [SaleController::class, 'getProductsByBrand'])->name('api.products.by-brand');
Route::get('/api/products/by-brand-model', [SaleController::class, 'getProductsByBrandModelQuery'])->name('api.products.by-brand-model-query');
Route::get('/api/products/by-brand-model/{brand}/{model}', [SaleController::class, 'getProductsByBrandModel'])->name('api.products.by-brand-model');
Route::get('/api/product-details/{product}', [SaleController::class, 'getProductDetails'])->name('api.product.details');

// Installments Routes
Route::get('/installments', [InstallmentSaleController::class, 'index'])->name('installments.index');
Route::get('/installments/overdue', [InstallmentController::class, 'overdue'])->name('installments.overdue');
Route::get('/installments/due-soon', [InstallmentController::class, 'dueSoon'])->name('installments.due-soon');
Route::post('/installments/{installment}/pay', [InstallmentController::class, 'pay'])->name('installments.pay');
Route::post('/installments/{installment}/penalty', [InstallmentController::class, 'addPenalty'])->name('installments.penalty');

// Payments Routes
Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
