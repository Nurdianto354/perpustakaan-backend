<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PeminjamanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/unautorized', function () {
    return response()->unauthorized();
})->name('unautorized');

Route::group(['middleware' => 'auth:api'], function ($router) {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Kategori Route
    Route::prefix('category')->group(function () {
        Route::get('/all', [CategoryController::class, 'index']);
        Route::post('/create', [CategoryController::class, 'store']);
        Route::get('/detail/{id}', [CategoryController::class, 'show']);
        Route::post('/update/{id}', [CategoryController::class, 'update']);
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy']);
    });

    // Buku Route
    Route::prefix('book')->group(function () {
        Route::get('/all', [BookController::class, 'index']);
        Route::post('/create', [BookController::class, 'store']);
        Route::get('/detail/{id}', [BookController::class, 'show']);
        Route::delete('/delete/{id}', [BookController::class, 'destroy']);
        
        // //import Export
        // Route::get('/export/excel', [BookController::class, 'exportBook']);
        // Route::get('/export/pdf', [BookController::class, 'exportBookPdf']);
        // Route::get('/download/template', [BookController::class, 'exportTemplate']);
        // Route::post('/import/excel', [BookController::class, 'importBook']);
    });

    // User Route
    Route::prefix('user')->group(function () {
        Route::get('/all', [UserController::class, 'index']);
        Route::get('/detail/{id}', [UserController::class, 'show']);
    });

    // Peminjaman Route
    Route::prefix('peminjaman')->group(function () {
        Route::get('/', [PeminjamanController::class, 'getPeminjamanBuku']);
        Route::get('/return', [PeminjamanController::class, 'getPengembalianBuku']);
        Route::post('/book/{$bukuId}/member/{$memberId}', [PeminjamanController::class, 'actionPinjamBuku']);
        Route::post('/book/{$bukuId}/accept', [PeminjamanController::class, 'acceptPeminjamanBuku']);
        Route::post('/book/{$bukuId}/return', [PeminjamanController::class, 'returnPeminjamanBuku']);
    });
});
