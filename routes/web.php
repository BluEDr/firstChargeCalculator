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

Auth::routes();

// Route::any('/{lang?}', [calculatingController::class,'showIndex'])->name('index')->middleware('auth');
Route::post('/submit-form', [calculatingController::class ,'processForm'])->name('processForm');

Route::any('/settings', [settingController::class, 'index'])->name('settings')->middleware(['auth','language']);
Route::get('/delete-row/{priceValue}', [calculatingController::class, 'deleteRow'])->name('delete.row')->middleware('auth');
Route::get('/search', [calculatingController::class, 'search'])->name('search')->middleware('auth');
Route::get('/invoice/{inv}', [calculatingController::class, 'invoice'])->name('invoice')->middleware('auth');
Route::get('/specialSearch', [calculatingController::class, 'search'])->name('special.search')->middleware('auth');
Route::any('/about', function() { 
    return view('about');
    })->name('about')->middleware('language'); //FIXME: otan kataxorite stin basi to language kai allazo selida to kanei null gia kapoion logo

Route::any('/contact', function() { 
    return view('contact'); 
    })->name('contact')->middleware('language'); 

Route::get('/hello/{id}', function($id) {
    return '<h1>Hello ' . $id . ' </h1>';
})->name('hello');

Route::get('/hello', function() {
    return view('helloSketo',['hello' => 'sketo']);
});

Route::get('/pyli', [calculatingController::class, 'pyli']);

// Route::get('/dokimi/{id}',  [calculatingController::class, 'index']);

Route::get('/pyli/{ttr}', [calculatingController::class, 'pyliTtr']);

Route::any('/insert', [calculatingController::class, 'insertValue'])->name('insert');
Route::any('/delete-post/{post}', [calculatingController::class, 'postDelete'])->name('post.del');


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/home2', function() {return view('home2');})->name('home2');


Route::any('/', [calculatingController::class, 'showIndex'])->name('index')->middleware(['auth','language']); //mpainei anagkastika teleytaio giati otan trexo gia paradigma to /settings to pernei oti to lang einai to settings kai trexei ayto anti gia to route settings. Opote paizei rolo i seira