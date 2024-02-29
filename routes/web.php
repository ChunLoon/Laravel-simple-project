<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostsController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', [PageController::class, 'index']);//  1.url,call controller which function & add use controller on top  --controller return page index
Route::get('/index', [PageController::class, 'index']); 
Route::get('/service', [PageController::class, 'services']); 

Route::get('/about', function () {           ///2.weburl ,function-if no call controller
    return view('about');                 //return the page 
});
Route::get('/users/{$id}', function ($id) {         //3.passing value
    return 'This is user'.$id;                
});

Route::resource('posts', PostsController::class);//4.  resourcepost url ,go post database controller -- save edit delete controller    //resorce-Eloquent model-contain post model,savedelete

Auth::routes(); //5.auth after login return view
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


