<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PouleController;
use App\Http\Controllers\TournementController;
use Illuminate\Support\Facades\Auth;
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
    return view('welcome');
});
Route::get('/poule/{id}', [PouleController::class, 'index'])->name('poule.index');
Auth::routes();


//Route::get('/tournement/{id}', [GameController::class, 'index'])->name('tournement.index');
Route::middleware('auth')->group(callback: function() {
    //routes die tot een blade leiden en waarvan de url ook daadwerkelijk zichtbaar wordt
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/tournement/{id}', [TournementController::class, 'index'])->name('tournement.index');
    //routes die eigenlijk alleen maar hulp-routes zijn
    //Route::get('/game/edit/{id}', [GameController::class, 'edit'])->name('game.edit');
    //Route::get('/editgamedata/{id}', [GameController::class, 'edit'])->name('editgamedata');
    //om up te daten
    Route::post('/updategamedata/{id}', [GameController::class, 'update'])->name('updategamedata');
    Route::post('/updatepouledata/{id}', [PouleController::class, 'update'])->name('updatepouledata');
    //om te editen en te showen
    Route::post('/editpouledata/{id}', [PouleController::class, 'edit'])->name('editpouledata');
    Route::post('/showpouledata/{id}', [PouleController::class, 'show'])->name('showpouledata');
    //om toe te voegen aan een formulier via jQuery
    Route::post('/adduserdata/{id}', [TournementController::class, 'addusers'])->name('adduserdata');
    //om een nieuw record op te slaan
    Route::post('/tournement/store/', [TournementController::class, 'store'])->name('storetournement');
    Route::post('/tournementuser/store/{id}', [TournementController::class, 'tournementuserstore'])->name('storetournementuser');
    //om een toernooi te deleten
    Route::post('/tournement/delete/{id}', [TournementController::class, 'delete'])->name('deletetournement');
    //om een toernooi te verwijderen
    Route::resource('tournements', TournementController::class);
});


/* 4 soorten:
1) je haalt iets op over een class: index
2) je maakt iets aan: create<tabelnaam>
    2.1: één record in een specifeke tabel: create<modelname>
3) je haalt de data op van iets om het via een formulier te updaten: edit<modelname>
4) je voert de update daadwerkelijk uit: update<modelname>
5) je wilt haalt data op voor een formulier waarmee je iets aan wilt maken: add<modelname>data

*/
