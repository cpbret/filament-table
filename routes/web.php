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

Route::get('/login', function () {
    $user = \App\Models\User::find(23525);
    \Illuminate\Support\Facades\Auth::login($user);
    return view('welcome');
});

Route::get('/', \App\Filament\Pages\ListUserPage::class)->name('settings-users.index');
