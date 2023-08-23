<?php

use Illuminate\Support\Facades\Route;
use App\Models\listing;
use App\Http\Controllers\calculatingController;
use App\Http\Controllers\HomeController;

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
    return view('welcome');
});


Route::get('/hello/{id}', function($id) {
    return '<h1>Hello ' . $id . ' </h1>';
});

Route::get('/hello', function() {
    return view('helloSketo',['hello' => 'sketo']);
});

Route::get('/pyli', [calculatingController::class, 'pyli']);

Route::get('/dokimi/{id}',  [calculatingController::class, 'index']);

Route::get('/pyli/{ttr}', [calculatingController::class, 'pyliTtr']);

Route::any('/insert', [calculatingController::class, 'insertValue'])->name('insert');
Route::any('/delete-post/{post}', [calculatingController::class, 'postDelete'])->name('post.del');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
