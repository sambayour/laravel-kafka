<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */
Route::group(['prefix' => 'v1', 'middleware' => 'throttle'], function () {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'register']);

    Route::group(['prefix' => 'products', 'middleware' => ['auth:sanctum']], function () {
        Route::get('/get-all-products', [ProductController::class, 'index'])->name('product.index');
        Route::post('/create-product', [ProductController::class, 'store'])->name('product.store');
        Route::patch('/update-product/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::get('/get-single-product/{id}', [ProductController::class, 'show'])->name('product.show');
        Route::delete('/delete-product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    });
});
