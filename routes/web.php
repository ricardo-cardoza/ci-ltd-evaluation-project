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
  Route::get('search', 'AdminController@search')->name('admin.search-1');

  // In case query is made from a page showing results and it's invalid.
  Route::get('query', 'AdminController@search')->name('admin.search-2');
  
  Route::post('query', 'AdminController@query')->name('admin.query');

  Route::get('clear-db', 'AdminController@clearDB')->name('admin.clear-db');
  Route::post('destroy', 'AdminController@destroy')->name('admin.destroy');
});
