<?php

use Illuminate\Support\Facades\Route;
use App\Models\listing;
use App\Http\Controllers;
use App\Http\Controllers\calculatingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\settingController;

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

Route::any('/', [calculatingController::class,'showIndex'])->name('index')->middleware('auth');
Route::post('/submit-form', [calculatingController::class ,'processForm'])->name('processForm');

Route::any('/settings', [settingController::class, 'index'])->name('settings')->middleware('auth');
Route::get('/delete-row/{priceValue}', [calculatingController::class, 'deleteRow'])->name('delete.row')->middleware('auth');

Route::get('/hello/{id}', function($id) {
    return '<h1>Hello ' . $id . ' </h1>';
});

Route::get('/hello', function() {
    return view('helloSketo',['hello' => 'sketo']);
});

Route::get('/pyli', [calculatingController::class, 'pyli']);

// Route::get('/dokimi/{id}',  [calculatingController::class, 'index']);

Route::get('/pyli/{ttr}', [calculatingController::class, 'pyliTtr']);

Route::any('/insert', [calculatingController::class, 'insertValue'])->name('insert');
Route::any('/delete-post/{post}', [calculatingController::class, 'postDelete'])->name('post.del');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/home2', function() {return view('home2');})->name('home2');