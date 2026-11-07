<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// API health check.
Route::get('/health', [HealthController::class, 'index']);

// Retrieve all products.
Route::get('/products', [ProductController::class, 'index']);

// Retrieve a single product.
Route::get('/products/{id}', [ProductController::class, 'show'])
    ->whereNumber('id');

// Create a new product.
Route::post('/products', [ProductController::class, 'store']);

// Sanctum-protected authenticated user endpoint.
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});