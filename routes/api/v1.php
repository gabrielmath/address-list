<?php

use App\Http\Controllers\Api\V1\{AddressController, CityController, UfController};
use Illuminate\Support\Facades\Route;

Route::get('/uf', [UfController::class, 'index'])->name('uf.index');
Route::get('/uf/{ufIbgeId}/cities', [CityController::class, 'index'])->name('cities.index');

Route::apiResource('addresses', AddressController::class);
