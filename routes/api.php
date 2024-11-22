<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\RegisterController;
use Illuminate\Support\Facades\Route;

/* Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
}); */


Route::controller(RegisterController::class)->group(function(){
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});


/**
 * Public URL to retrieve orders by table.
 * @exemple GET `APP_URL/api/orders?table=T4`
 */
Route::get('/orders', [OrderController::class, 'ordersByTableCode']);

/**
 * Endpoints for the bartender / staff.
 * @exemple GET `APP_URL/api/admin/orders`
 */
Route::middleware('auth:sanctum')->prefix('admin')->group(function() {
    // Versioning ?php artisan route:list --path=api
    // Route::apiResource('orders', OrderController::class) vérifie le fait que ce soit un ULID ?

    Route::controller(OrderController::class)->group(function() {
        Route::get('/orders', 'index');
        Route::get('/orders/{order}', 'show')->whereUlid('order');
        Route::post('/orders', 'store');
        Route::patch('/orders/{order}', 'update')->whereUlid('order');
        Route::delete('/orders/{order}', 'destroy')->whereUlid('order');
    });
});
