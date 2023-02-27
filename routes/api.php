<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
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

// Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Buku Route
    Route::prefix('book')->group(function () {
        Route::get('/all', [BookController::class, 'getAllBooks']);
        Route::get('/{$id}', [BookController::class, 'getBook']);
        //CRUD
        Route::get('/create', [BookController::class, 'create']);
        Route::post('/create', [BookController::class, 'store']);
        Route::get('/{$id}/update', [BookController::class, 'show']);
        Route::put('/{$id}/update', [BookController::class, 'update']);
        Route::delete('/{$id}/delete', [BookController::class, 'destroy']);
        //import Export
        Route::get('/export/excel', [BookController::class, 'exportBook']);
        Route::get('/export/pdf', [BookController::class, 'exportBookPdf']);
        Route::get('/download/template', [BookController::class, 'exportTemplate']);
        Route::post('/import/excel', [BookController::class, 'importBook']);
    });

    // Kategori Route
    Route::prefix('category')->group(function () {
        Route::get('/{$category}/book/all', [BookController::class, 'getBooksByCategory']);
        Route::get('/all', [CategoryController::class, 'getAllCategories']);
        Route::get('/{$id}', [CategoryController::class, 'getDetailCategory']);
        //CRUD
        Route::post('/create', [CategoryController::class, 'store']);
        Route::put('/{$id}/update', [CategoryController::class, 'update']);
        Route::delete('/{$id}/delete', [CategoryController::class, 'destroy']);
    });

    // User Route
    Route::prefix('user')->group(function () {
        Route::get('/all', [UserController::class, 'getAllUsers']);
        Route::get('/{$id}', [UserController::class, 'getUser']);
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
