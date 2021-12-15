<?php

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

Route::get('/', 'MainController@index')->name('main.index');

Route::post('/store_user', 'MainController@store_user')->name('main.store_user');
Route::post('/store_transaction', 'MainController@store_transaction')->name('main.store_transaction');

Route::get('/download_file_user', 'MainController@download_file_user')->name('main.download_file_user');
Route::get('/download_file_transaction', 'MainController@download_file_transaction')->name('main.download_file_transaction');
Route::get('/download_file_result', 'MainController@download_file_result')->name('main.download_file_result');
