<?php

use App\Http\Controllers\EquipoController;
use Illuminate\Support\Facades\Route;

Route::get('/equipos', [EquipoController::class, 'index']);
Route::post('/validar-equipos', [EquipoController::class, 'validar']);
