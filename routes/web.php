<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;

// Autenticação
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route('clientes.index'));

    Route::resource('clientes', ClienteController::class)->except(['show']);

    // AJAX: consulta CEP
    Route::get('/cep/{cep}', [ClienteController::class, 'consultarCep'])->name('cep.consultar');
});
