<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\SalaryController;
use App\Http\Controllers\Api\CustomeController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;


// Public routes (no JWT authentication required)
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    // Password reset routes
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Protected routes (JWT authentication required)
Route::group([
    'middleware' => 'jwt.auth',  // Use the correct alias for your middleware
    'prefix' => 'auth'
], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
});

// Alternative protected route for testing (single route with middleware applied)
Route::middleware('jwt.auth')->get('/user', function (Request $request) {
    return response()->json($request->user());
});

Route::apiResource('employees', EmployeeController::class);
Route::apiResource('suppliers', SupplierController::class);
Route::apiResource('categories', CategoryController::class);

Route::apiResource('products', ProductController::class);
Route::get('/products/barcode/{barcode}', [ProductController::class, 'showByBarcode']);
Route::put('/products/barcode/{barcode}', [ProductController::class, 'updateByBarcode']);
Route::delete('/products/barcode/{barcode}', [ProductController::class, 'destroyByBarcode']);
Route::get('/products/search', [ProductController::class, 'searchByName']);




Route::apiResource('expenses', ExpenseController::class);

// Custom routes (must come before apiResource)
Route::get('/salaries/search', [SalaryController::class, 'search']);

// Standard CRUD routes
Route::apiResource('salaries', SalaryController::class);

Route::apiResource('customers', CustomerController::class);


// In routes/api.php
Route::apiResource('orders', OrderController::class);

Route::post('/orders/{order}/return', [OrderController::class, 'returnOrder']);

Route::apiResource('sales', SaleController::class);

Route::post('/sales/{sale}/return', [SaleController::class, 'returnSale']);
