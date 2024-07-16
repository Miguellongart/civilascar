<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\TournamentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', [FrontController::class, 'home'])->name('front.home');
Route::get('/contacto', [FrontController::class, 'Contact'])->name('front.contact');
Route::get('/sobre-nosotros', [FrontController::class, 'About'])->name('front.about');
Route::get('/escuelita', [FrontController::class, 'LittleSchool'])->name('front.school');
Route::get('/liga-cafetera', [FrontController::class, 'tournament'])->name('front.tournament');
Route::get('/tournament', [FrontController::class, 'tournament'])->name('front.tournament.index');
Route::post('/tournament/register', [FrontController::class, 'registerTeam'])->name('front.tournament.register');

Route::get('/tournament/{tournamentId}/teams', [FrontController::class, 'getTeamsByTournament'])->name('front.tournament.getTeamsByTournament');
Route::get('/tournament/{tournamentId}', [FrontController::class, 'getinfoByTournament'])->name('front.tournament.getTeamsByTournament');

Route::get('/inscripcion/liga-cafetera-2024-2', [FrontController::class, 'inscription'])->name('front.inscription');
// Route::get('/contacto', [FrontController::class, 'Contact'])->name('front.contact');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('admin.user.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.user.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.user.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.user.destroy');

    Route::get('tournament', [TournamentController::class, 'index'])->name('admin.tournament.index');
    Route::get('tournament/create', [TournamentController::class, 'create'])->name('admin.tournament.create');
    Route::post('tournament', [TournamentController::class, 'store'])->name('admin.tournament.store');
    Route::get('tournament/{id}', [TournamentController::class, 'show'])->name('admin.tournament.show');
    Route::get('tournament/{id}/edit', [TournamentController::class, 'edit'])->name('admin.tournament.edit');
    Route::put('tournament/{id}', [TournamentController::class, 'update'])->name('admin.tournament.update');
    Route::delete('tournament/{id}', [TournamentController::class, 'destroy'])->name('admin.tournament.destroy');
    Route::get('admin/tournament/{tournament}/add-teams', [TournamentController::class, 'addTeams'])->name('admin.tournament.addTeams');
    Route::post('admin/tournament/{tournament}/store-teams', [TournamentController::class, 'storeTeams'])->name('admin.tournament.storeTeams');
    Route::get('admin/tournament/{tournament}/teams', [TournamentController::class, 'listTeams'])->name('admin.tournament.listTeams');


    Route::get('team', [TeamController::class, 'index'])->name('admin.team.index');
    Route::get('team/create', [TeamController::class, 'create'])->name('admin.team.create');
    Route::post('team', [TeamController::class, 'store'])->name('admin.team.store');
    Route::get('team/{id}', [TeamController::class, 'show'])->name('admin.team.show');
    Route::get('team/{id}/edit', [TeamController::class, 'edit'])->name('admin.team.edit');
    Route::put('team/{id}', [TeamController::class, 'update'])->name('admin.team.update');
    Route::delete('team/{id}', [TeamController::class, 'destroy'])->name('admin.team.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
});
require __DIR__.'/auth.php';
