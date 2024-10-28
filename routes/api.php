<?php

use App\Http\Controllers\Admin\BusinessController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Business\ServiceController;
use App\Http\Controllers\ReviewController;
use App\Models\User;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['admin','auth:sanctum'])->group(function () {
    Route::apiResource('/business', BusinessController::class);
    Route::apiResource('/user', UserController::class);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/service', ServiceController::class);
    Route::apiResource('/booking', BookingController::class);
    Route::apiResource('/review', ReviewController::class);
    Route::get('/business_review/{id}', [ReviewController::class, 'business_review']);
});
