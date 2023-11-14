<?php

use App\Http\Controllers\FinalgameController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PitchController;
use App\Http\Controllers\PouleController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\TeamController;
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

Route::get('/', function () { return view('welcome'); });
Route::get('/poules/{id}', [PouleController::class, 'show'])->name('poules.show');
Route::get('/tournement_program/{id}', [TournementController::class, 'program'])->name('tournement.program');
Route::get('/tournement_videowall/{id}', [TournementController::class, 'videowall'])->name('tournement.videowall');
Route::get('/tournement_gamesheets/{id}', [TournementController::class, 'gamesheets'])->name('tournement.gamesheets');
Route::get('/tournement_gamesexport/{id}', [TournementController::class, 'export'])->name('tournement.gamesexport');
//helper class die alle routes genereert die nodig zijn voor authenticatie, inclusief logout
Auth::routes();
Route::middleware('auth')->group(callback: function() {
    //routes die gebruikt worden in js-functies ('/js/....') of bij een ajax-call ('/aj/......') en die dus niet aangeroepen kunnen worden met de naam van een route en dus ook geen naam hoeven hebben
    Route::match(['put', 'get', 'post'], '/js/tournements/store', [TournementController::class, 'store']);
    Route::get('/aj/tournement_users/add', [TournementController::class, 'addusers']);
    Route::get( '/aj/games/deletescore', [GameController::class, 'deletescore']);
    Route::match(['put', 'get'], '/aj/games/update', [GameController::class, 'update']);
    Route::get('/aj/teams/edit', [TeamController::class, 'edit']);
    Route::get('/aj/pitches/edit', [PitchController::class, 'edit']);
    //Route::post('/aj/rounds/update', [RoundController::class, 'update']);
    Route::match(['put', 'get'], '/aj/rounds/update', [RoundController::class, 'update']);
    Route::post('/js/rounds/store', [RoundController::class, 'store']);
    Route::get('/aj/finalgames/edit', [FinalgameController::class, 'edit']);
    //nodig voor het koppelen van teams aan finalerondes op basis van poule en ranking
    Route::get('/aj/teams/stand', [TeamController::class, 'stand']);
    //voor de stand van de teams in een poule
    Route::get('/aj/teams/show', [TeamController::class, 'show']);
    //voor de teams in finalgame.edit.js
    Route::get('/aj/teams/showinjs', [TeamController::class, 'showinjs']);
    Route::post('/js/finalgames/update', [FinalgameController::class, 'update']);
    Route::delete('/js/finalgames/destroy', [FinalgameController::class, 'destroy']);
    Route::delete('/js/tournements/destroy', [TournementController::class, 'destroy']);
    Route::delete('/js/tournement_user/destroy', [TournementController::class, 'destroytournementuser']);
    //routes die tot een blade leiden en waarvan de url ook daadwerkelijk zichtbaar wordt
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/tournement/{id}/{poule_id}', [TournementController::class, 'show'])->name('tournement.show');
    //om de teamnamen in een poule aan te passen
    Route::post('/teams/{poule_id}/update', [TeamController::class, 'update'])->name('teams.update');
    Route::post('/pitches/{tournement_id}/update', [PitchController::class, 'update'])->name('pitches.update');
    //om een user te koppelen aan een toernooi
    Route::post('/tournement_user/store/{id}', [TournementController::class, 'tournement_userstore'])->name('tournement_user.store');
});


/* 4 soorten:
1) je haalt iets op over een class: index
2) je maakt iets aan: create<tabelnaam>
    2.1: één record in een specifeke tabel: create<modelname>
3) je haalt de data op van iets om het via een formulier te updaten: edit<modelname>
4) je voert de update daadwerkelijk uit: update<modelname>
5) je wilt haalt data op voor een formulier waarmee je iets aan wilt maken: add<modelname>data

*/
/*
Verb	URI	                    Action	        Controller function     Route Name              Blade Name
GET	    /users	                Users list	    index()                 users.index             index.blade
POST	/users/add	            Add a new user  add()                   users.add
of
POST	/users/store	        Add a new user  store()                 users.store
GET	    /users/{user}	        Get user	    show()                  users.show              show_blade of in modal
GET     /users/{user}/onderdeel Get user        show_onderdeel()        users.show_onderdeel    show_onderdeel.blade of in modal
GET	    /users/{user}/edit	    Edit user	    edit()                  users.edit              edit.blade of in modal
PUT	    /users/{user}	        Update user	    update()                users.update
DELETE	/users/{user}	        Delete user	    destroy()               users.destroy           destroy.blade of in modal
*/
