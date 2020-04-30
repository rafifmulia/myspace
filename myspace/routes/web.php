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
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', 'Auth@loginView')->name('login');
Route::post('/login/auth', 'Auth@web_login')->name('web.auth.login');
Route::post('/refresh/auth', 'Auth@web_refresh')->name('web.auth.refresh');
Route::get('/register', 'Auth@registerView')->name('register');
Route::post('/register/auth', 'Auth@register')->name('web.auth.register');
Route::get('/logout', 'Auth@logout')->name('logout');

// auth session
Route::middleware(['auth.cookie'])->group(function () {
    Route::get('/space', 'Pages@space_index')->name('space.index');
    Route::get('/space/create', 'Pages@space_create')->name('space.create');
    Route::get('/space/{id}/edit', 'Pages@space_edit')->name('space.edit');
    Route::get('/space/{id}/direction', 'Pages@space_direction')->name('space.direction');
});
