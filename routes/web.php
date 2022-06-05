<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

});

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/forum', 'HomeController@index');
Route::get('/posts/new', 'HomeController@index');
Route::get('/schedule', 'HomeController@index');
Route::post('/getSchedule', 'ScheduleController@getSchedule');
Route::get('/doctors', 'HomeController@index');
Route::get('/appointments', 'HomeController@index');
Route::resources([
    'posts' => 'PostController',
    'comments' => 'CommentController',
    'likes' => 'LikeController',
    'schedules' => 'ScheduleController',
    'messages' => 'MessageController',
    'users' => 'UserController',
    'specialists' => 'SpecialistController',
    'appointments' => 'AppointmentController',
]);
Route::get('/csrf', function () {
    return csrf_token();
});
