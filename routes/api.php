<?php


use App\Http\Controllers\EventController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:api')->group(function() {
    Route::get('/peserta', [PesertaController::class, 'index']);
    Route::post('/peserta/create', [PesertaController::class, 'store']);
    Route::post('/peserta/update/{id}', [PesertaController::class, 'update']);
    Route::delete('/peserta/delete/{id}', [PesertaController::class, 'destroy']);

    Route::get('/event', [EventController::class, 'index']);
    Route::post('/event/create', [EventController::class, 'store']);
    Route::post('/event/update/{id}', [EventController::class, 'update']);
    Route::delete('/event/delete/{id}', [EventController::class, 'destroy']);

    Route::get('/pengeluaran_event', [PengeluaranController::class, 'index']);
    Route::post('/pengeluaran_event/create', [PengeluaranController::class, 'store']);
    Route::post('/pengeluaran_event/update/{id}', [PengeluaranController::class, 'update']);
    Route::delete('/pengeluaran_event/delete/{id}', [PengeluaranController::class, 'destroy']);
    Route::get('/pengeluaran_event/search/{nama_pengeluaran}', [PengeluaranController::class, 'search']);

    route::post('/logout', [UserController::class, 'logout']);

});