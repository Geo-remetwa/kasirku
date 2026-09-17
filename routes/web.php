<?php

use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by bootstrap/app.php and all of them start off as
| assigned to the "web" middleware group.
|
*/

// Registers login/register/password-reset/verify-email routes (laravel/ui).
Auth::routes(['verify' => true]);

Route::prefix('/admin')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', [HomeController::class, 'index']);
        Route::resource('user', UserController::class);
        Route::resource('product', ProductController::class);
        Route::resource('product-category', ProductCategoryController::class);
        Route::resource('customer', CustomerController::class);
        Route::resource('coupon', CouponController::class);

        Route::get('/company', [CompanyProfileController::class, 'index'])->name('companyProfile.index');
        Route::post('/company', [CompanyProfileController::class, 'save'])->name('companyProfile.save');
    });

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::post('/sale/getCoupon', [SaleController::class, 'getCoupon'])->name('sale.getCoupon');
    Route::resource('sale', SaleController::class);

    Route::post('/transaction/report', [TransactionController::class, 'report'])->name('transaction.report');
    Route::get('/struk/{transaction_code?}', [TransactionController::class, 'struk'])->name('transaction.struk');
    Route::resource('transaction', TransactionController::class)->except(['create']);
    Route::get('/transaction/create/{transaction_code?}', [TransactionController::class, 'create'])->name('transaction.create');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
