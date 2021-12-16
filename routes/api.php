<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/course', 'API\CourseController@index');
Route::get('/course/schedules', 'API\CourseController@course_schedules');

Route::post('/schedule/update', 'API\ScheduleController@update');

Route::delete('/schedule/destroy', 'API\ScheduleController@destroy');
