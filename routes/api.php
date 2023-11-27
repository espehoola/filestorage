<?php
declare(strict_types=1);

use App\Http\Controllers\V1\FileController;
use App\Http\Controllers\V1\HealthCheckController;
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

Route::post('/v1/file', [FileController::class, 'create']);
Route::delete('/v1/file/{uuid}', [FileController::class, 'delete']);
Route::get('/v1/file/{uuid}', [FileController::class, 'downloadFile'])->name('file.get');
Route::get('/v1/file/{uuid}/info', [FileController::class, 'getByUuid']);
Route::get('/v1/health-check', [HealthCheckController::class, 'healthCheck']);
