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
Route::get('/', 'DashboardController@index')->name('dashboard.index');
Route::resource('document', 'DocumentController', ['except' => 'index', 'show']);
Route::get('document/{document?}/stahnout', 'DocumentController@download')->name('document.download');
Route::resource('folder', 'FolderController', ['only' => ['store', 'update']]);
Route::get('folder/{folder?}', 'FolderController@getContents')->name('folder.contents');