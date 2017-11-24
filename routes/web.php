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

Auth::routes();

Route::resource('documents', 'DocumentController', ['except' => ['index']]);
Route::get('documents/{document}/download', 'DocumentController@download')->name('documents.download');
Route::get('/', 'FolderController@index')->name('folders.index');
Route::resource('folders', 'FolderController', ['except' => ['index', 'create', 'edit']]);
Route::get('keywords', 'KeywordController@index')->name('keywords.index');