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

Route::get('/', 'Auth\LoginController@showLoginForm');

Auth::routes();

Route::group(['prefix' => 'admin'], function() {
  Route::get('home', 'AdminController@index')->name('admin.home');
  Route::get('upload', 'AdminController@upload')->name('admin.upload');
  Route::post('import', 'AdminController@import')->name('admin.import');
});
