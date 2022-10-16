<?php declare(strict_types=1);

use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [JobVacancyController::class, 'index']);

    Route::group(['prefix' => 'job'], function () {
        Route::get('all', [JobVacancyController::class, 'getAll']);
        Route::post('store', [JobVacancyController::class, 'store'])->name('limit');
        Route::post('send', [JobVacancyController::class, 'sendJobVacancyResponse']);
        Route::put('like', [JobVacancyController::class, 'like']);
    });

    Route::group(['prefix' => 'tag'], function () {
        Route::post('store ', [TagController::class, 'store']);
        Route::get('all ', [TagController::class, 'getAll']);
    });
});

Auth::routes();
