<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\RazorpayWebhookController;
Route::post('/upload-attendance', [AttendanceController::class, 'uploadAttendance']);
Route::post('/webhook/receive', [WebhookController::class, 'flyHihandle']);
Route::post('/payment/webhook', [RazorpayWebhookController::class, 'handleWebhook']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
