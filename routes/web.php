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

Route::get('/', 'BookmarkController@show')->name('index');

Route::get('/tag/{tag}', 'BookmarkController@showTag')->name('tag');

Route::post('/search', 'BookmarkController@searchForm')->name('searchForm');
Route::get('/search/{query}', 'BookmarkController@search')->name('search');
