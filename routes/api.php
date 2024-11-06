<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AduanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\ContactController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/profile', [AuthController::class, 'showProfile']);
Route::middleware('auth:sanctum')->put('/profile', [AuthController::class, 'updateProfile']);

// Aduan routes
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('aduan', [AduanController::class, 'store']);
    Route::get('aduans', [AduanController::class, 'index']);
    Route::put('aduans/{kek}/{Status}', [AduanController::class, 'update'])->middleware('admin');
    
});

Route::get('aduan/filter', [AduanController::class, 'filterByLocation']);
Route::post('/contact-us', [ContactController::class, 'sendMessage']);
Route::get('/leaderboard', [ChallengeController::class, 'leaderboard']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('aduan/report/pdf', [ReportController::class, 'generatePdf'])->middleware('admin');
    Route::get('aduan/report/excel', [ReportController::class, 'generateExcel'])->middleware('admin');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('kategoris', [KategoriController::class, 'getKategoris']);
    Route::post('kategoris', [KategoriController::class, 'storeKategori'])->middleware('admin');
    Route::put('kategoris/{id}', [KategoriController::class, 'updateKategori'])->middleware('admin');
    Route::delete('kategoris/{id}', [KategoriController::class, 'destroyKategori'])->middleware('admin');
});
