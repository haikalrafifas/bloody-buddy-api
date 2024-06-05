<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
// use App\Http\Controllers\StatusController;
use App\Http\Controllers\DonorApplicantController;
use App\Http\Controllers\ScheduleController;

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

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::controller(DonorController::class)->group(function () {
    Route::post('donor-schedule/{uuid}', [DonorApplicantController::class, 'update']);
    Route::resource('donors', DonorApplicantController::class);
});

Route::controller(ScheduleController::class)->group(function () {
    Route::resource('schedules', ScheduleController::class);
});
