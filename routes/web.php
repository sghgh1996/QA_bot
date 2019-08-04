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

Route::get('/', 'AnswerController@getBotInterfaceHome');

Route::get('/dashboard', 'DashboardController@getDashboard');

Route::post('api/answer', 'AnswerController@answer');

Route::group(['prefix' => 'dashboard'], function () {
    Route::get('/', function () {
        return redirect('dashboard/results');
    });
    Route::get('/results', 'DashboardController@getDashboard');
    Route::group(['prefix' => 'search'], function () {
        Route::get('/questions', 'DashboardController@getTestQuestions');
    });
});