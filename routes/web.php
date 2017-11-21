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

Route::resource('document', 'DocumentController', ['except' => ['index', 'show']]);
Route::get('document/{document}/download', 'DocumentController@download')->name('document.download');
Route::get('/', 'FolderController@index')->name('folder.index');
Route::resource('folder', 'FolderController', ['except' => ['index', 'create', 'edit']]);