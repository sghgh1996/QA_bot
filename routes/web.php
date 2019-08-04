<?php

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

Route::match(['get', 'post'], '/botman', 'BotManController@handle');

Route::get('/google/{query}', '\App\QAProcessing\Google@getResult');
Route::post('api/answer', 'AnswerController@answer');

// Route::get('/', 'AnswerController@getBotInterfaceHome');

Route::get('/', function () {
    return redirect('dashboard');
});

Route::group(['prefix' => 'dashboard'], function () {
    Route::get('/', function () {
        return redirect('dashboard/results');
    });
    Route::get('/results', 'DashboardController@getDashboard');
    Route::group(['prefix' => 'search'], function () {
        Route::group(['prefix' => 'questions'], function () {
            Route::get('/', 'DashboardController@getTestQuestions');
            Route::get('/question/{id}', 'DashboardController@analyzeQuestion');
        });
    });
});