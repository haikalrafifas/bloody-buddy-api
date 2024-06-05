<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonorApplicantController;
use App\Http\Controllers\DonorScheduleController;
use App\Http\Controllers\ScheduleController;

use App\Http\Controllers\LocationController;
use App\Http\Controllers\DonorStatusController;

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

Route::controller(DonorApplicantController::class)->group(function () {
    Route::post('donor-applicant/{uuid}', [DonorApplicantController::class, 'update']);
    Route::post('donor-applicant/{uuid}/delete', [DonorApplicantController::class, 'destroy']);
    Route::resource('donor-applicants', DonorApplicantController::class);
});

Route::controller(ScheduleController::class)->group(function () {
    Route::post('schedule/{uuid}', [ScheduleController::class, 'update']);
    Route::post('schedule/{uuid}/delete', [ScheduleController::class, 'destroy']);
    Route::resource('schedules', ScheduleController::class);
});

Route::controller(LocationController::class)->group(function () {
    Route::post('location/{uuid}', [LocationController::class, 'update']);
    Route::post('location/{uuid}/delete', [LocationController::class, 'destroy']);
    Route::resource('locations', LocationController::class);
});

Route::controller(DonorStatusController::class)->group(function () {
    Route::post('donor-status/{uuid}', [DonorStatusController::class, 'update']);
    Route::post('donor-status/{uuid}/delete', [DonorStatusController::class, 'destroy']);
    Route::resource('donor-statuses', DonorStatusController::class);
});
