<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LotacaoController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServidorEfetivoController;
use App\Http\Controllers\ServidorTemporarioController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;


Route::get('/teste', function () {
    echo 'teste';
});

Route::post('/authenticate', [AuthController::class, 'authenticate']);
Route::get('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'auth.refresh'])->group(function () {

    // Unidade
    Route::get('/unidade/{unid_id}/servidores-efetivos', [UnidadeController::class, 'getServidoresEfetivos']);
    Route::resource('unidade', UnidadeController::class);

    // Servidor Efetivo
    Route::resource('servidor-efetivo', ServidorEfetivoController::class);

    // Servidor Temporário
    Route::resource('servidor-temporario', ServidorTemporarioController::class);

    // lotação
    Route::resource('lotacao', LotacaoController::class);

    // Search
    Route::get('/search', [SearchController::class, 'servidor']);

    // Upload
    Route::post('/upload', [UploadController::class, 'upload']);
});
