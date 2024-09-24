<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/balance', [ApiController::class, 'getBalance']);
Route::post('/reset', [ApiController::class, 'reset']);
Route::post('/event', [ApiController::class, 'postEvent']);
