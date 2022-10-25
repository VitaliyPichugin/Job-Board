<?php declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JobVacancyController;
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

Route::group(['middleware' => 'api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

    Route::group(['prefix' => 'job'], function () {
        Route::post('create', [JobVacancyController::class, 'createJobVacancy']);
        Route::post('update/{id}', [JobVacancyController::class, 'updateJobVacancy']);
        Route::delete('{id}', [JobVacancyController::class, 'deleteJobVacancy']);
        Route::post('send-response', [JobVacancyController::class, 'sendJobVacancyResponse']);
        Route::delete('response/{id}', [JobVacancyController::class, 'deleteResponse']);
        Route::get('liked', [JobVacancyController::class, 'getLiked']);

        Route::get('all', [JobVacancyController::class, 'index'])->name('index');
        Route::get('detail/{id}', [JobVacancyController::class, 'show'])->name('show');
        Route::get('list-of-job-vacancies', [
            JobVacancyController::class,
            'getListJobVacancies'
        ])->name('getListJobVacancies');
    });
});
