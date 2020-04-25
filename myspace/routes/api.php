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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// get token
Route::post('/login/auth', 'Api\Auth@login')->name('auth.login');
Route::post('/refresh/auth', 'Api\Auth@refresh')->name('auth.refresh');

// auth jwt from authorization: bearer <token> OR from cookie (for web application itself)
Route::middleware(['auth.jwt'])->group(function () {
    Route::get('/example', function () {
        echo "You can access this";
    });

    Route::get('/space/neighbordhoods', 'Api\Spaces@neighbord');
    Route::post('/space/create', 'Api\Spaces@create');
    Route::put('/space/update', 'Api\Spaces@update');
    Route::delete('/space/delete', 'Api\Spaces@delete');
});