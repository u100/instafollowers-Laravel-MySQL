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

Route::get('/', function () {
    return redirect('all-followers');
});

Auth::routes();

Route::get('/all-followers', 'FollowerController@displayAllFollowers')->name('all-followers');
Route::get('/followers-and-following', 'FollowerController@displayFollowersAndFollowing')->name('followers-and-following');
Route::get('/followers', 'FollowerController@displayFollowers')->name('followers');
// Route::get('/following', 'FollowerController@displayFollowing')->name('following');
Route::get('/past-followers', 'FollowerController@displayPastFollowers')->name('past-followers');
Route::get('/past-following', 'FollowerController@displayPastFollowing')->name('past-following');
