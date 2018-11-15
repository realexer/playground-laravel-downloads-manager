<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/downloads/all', 'Api\\DownloadsController@all')->name('api.downloads.all');
Route::post('/downloads/add', 'Api\\DownloadsController@add')->name('api.downloads.add');
Route::get('/downloads/{id}', 'Api\\DownloadsController@get')->name('api.downloads.get');