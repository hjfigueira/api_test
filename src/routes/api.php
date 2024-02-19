<?php

use App\Http\Controllers\FundDuplicatesApiController;
use App\Http\Controllers\FundApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

Route::addRoute('GET', 'healthcheck', function () {
    return new JsonResponse(['status' => 'OK']);
});

Route::apiResource('duplicates', FundDuplicatesApiController::class);
Route::apiResource('fund', FundApiController::class);
