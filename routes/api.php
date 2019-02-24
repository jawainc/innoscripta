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
Route::get('/search', 'API\CompaniesController@list_all');
Route::post('/search', 'API\CompaniesController@search');
Route::delete('/bill', 'API\CompaniesController@remove_bill');
Route::put('/bill', 'API\CompaniesController@update_bill');
Route::post('/company', 'API\CompaniesController@store');
Route::put('/company', 'API\CompaniesController@update');
Route::get('/company/{id}', 'API\CompaniesController@show');
Route::post('/company/search/names', 'API\CompaniesController@search_names');