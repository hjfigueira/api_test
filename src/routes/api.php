<?php

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::addRoute('GET', 'healthcheck', function(Request $request){
    return new JsonResponse(['status' => 'OK']);
});

Route::apiResource('company', \App\Http\Controllers\CompanyApiController::class);
Route::apiResource('fund', \App\Http\Controllers\FundApiController::class);
