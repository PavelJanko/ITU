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
Route::get('documents/{document}/sharing', 'DocumentController@sharingEdit')->name('document.sharingEdit');
Route::post('documents/{document}/sharing', 'DocumentController@sharingUpdate')->name('document.sharingUpdate');
Route::get('/', 'FolderController@index')->name('folders.index');
Route::resource('folders', 'FolderController', ['except' => ['index', 'create', 'edit']]);
Route::get('folders/{folder}/sharing', 'FolderController@sharingEdit')->name('folder.sharingEdit');
Route::post('folders/{folder}/sharing', 'FolderController@sharingUpdate')->name('folder.sharingUpdate');
Route::get('groups/edit', 'GroupController@edit')->name('groups.edit');
Route::post('groups/{group}/add-member', 'GroupController@addMember')->name('groups.addMember');
Route::get('groups/{group}/edit-members', 'GroupController@editMembers')->name('groups.editMembers');
Route::get('groups/{group}/leave', 'GroupController@leave')->name('groups.leave');
Route::resource('groups', 'GroupController', ['except' => ['index', 'create', 'edit']]);
Route::get('keywords', 'KeywordController@index')->name('keywords.index');
Route::get('sharing', 'GroupController@index')->name('groups.index');