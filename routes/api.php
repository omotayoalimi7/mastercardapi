<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



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

Route::post('/createcompany', 'CompanyController@createCompany');
Route::post('/createuser', 'UserController@createUser');
Route::post('/getcompanyusers', 'UserController@getCompanyusers');
Route::post('/getallcompanies', 'CompanyController@getAllCompanies');
Route::post('/getcompanybyid', 'CompanyController@getCompanybyId');
Route::post('/login', 'UserController@LoginUser');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
