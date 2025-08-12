<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\FixtureController;
use App\Http\Controllers\Admin\LittleSchoolController;
use App\Http\Controllers\Admin\PhotoGalleryController;
use App\Http\Controllers\Admin\PlayerController;
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

Route::get('/inscripcion/liga-cafetera-2024-3', [FrontController::class, 'inscription'])->name('front.inscription');
// Route::get('/contacto', [FrontController::class, 'Contact'])->name('front.contact');
Route::get('/register', [FrontController::class, 'showForm'])->name('registration.form');
Route::post('/registerLitle', [FrontController::class, 'register'])->name('registration.register');

Route::get('/equipo/{teamId}', [FrontController::class, 'teamPage'])->name('front.team.show');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('admin.user.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.user.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.user.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.user.destroy');

    Route::put('/users/rol/{rol}', [UserController::class, 'role'])->name('user.role');
    Route::put('/users/perm/{permission}', [UserController::class, 'permission'])->name('user.permission');

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


    // Route::get('team', [TeamController::class, 'index'])->name('admin.team.index');
    // Route::get('team/create', [TeamController::class, 'create'])->name('admin.team.create');
    // Route::post('team', [TeamController::class, 'store'])->name('admin.team.store');
    // Route::get('team/{idTeam}/{idTournament}', [TeamController::class, 'show'])->name('admin.team.show');
    // Route::get('team/{id}/edit', [TeamController::class, 'edit'])->name('admin.team.edit');
    // Route::get('team/{id}/edit', [TeamController::class, 'show'])->name('admin.team.show');
    // Route::put('team/{id}', [TeamController::class, 'update'])->name('admin.team.update');
    // Route::delete('team/{id}', [TeamController::class, 'destroy'])->name('admin.team.destroy');
    Route::resource('team', TeamController::class)->names('admin.team');
    Route::post('team/transfer-player', [TeamController::class, 'transferPlayer'])->name('admin.team.transferPlayer');


    Route::get('tournaments/{tournament}/fixtures', [FixtureController::class, 'index'])->name('admin.fixture.index');
    Route::get('fixture/create', [FixtureController::class, 'create'])->name('admin.fixture.create');
    Route::post('fixture', [FixtureController::class, 'store'])->name('admin.fixture.store');
    Route::get('fixture/{id}', [FixtureController::class, 'show'])->name('admin.fixture.show');
    Route::get('fixture/{id}/edit', [FixtureController::class, 'edit'])->name('admin.fixture.edit');
    Route::put('fixture/{id}', [FixtureController::class, 'update'])->name('admin.fixture.update');
    Route::delete('fixture/{id}', [FixtureController::class, 'destroy'])->name('admin.fixture.destroy');
    Route::get('fixture/{id}/generate', [FixtureController::class, 'createFixture'])->name('admin.fixture.createFixture');

    // Rutas para editar y eliminar jugadores
    Route::get('player/{id}/edit', [PlayerController::class, 'edit'])->name('admin.player.edit');
    Route::put('player/{id}', [PlayerController::class, 'update'])->name('admin.player.update');
    Route::delete('player/{id}', [PlayerController::class, 'destroy'])->name('admin.player.destroy');

    Route::get('tournaments/{tournamentId}/galleries', [PhotoGalleryController::class, 'index'])->name('admin.gallery.index');
    Route::get('tournaments/{tournamentId}/galleries/create', [PhotoGalleryController::class, 'create'])->name('admin.gallery.create');
    Route::post('tournaments/{tournamentId}/galleries', [PhotoGalleryController::class, 'store'])->name('admin.gallery.store');
    Route::delete('galleries/{id}', [PhotoGalleryController::class, 'destroy'])->name('admin.gallery.destroy');

    Route::get('littleSchool', [LittleSchoolController::class, 'index'])->name('admin.littleSchool.index');
    Route::get('littleSchool/create', [LittleSchoolController::class, 'create'])->name('admin.littleSchool.create');
    Route::post('littleSchool', [LittleSchoolController::class, 'store'])->name('admin.littleSchool.store');
    Route::get('littleSchool/{id}', [LittleSchoolController::class, 'show'])->name('admin.littleSchool.show');
    Route::get('littleSchool/{id}/edit', [LittleSchoolController::class, 'edit'])->name('admin.littleSchool.edit');
    Route::put('littleSchool/{id}', [LittleSchoolController::class, 'update'])->name('admin.littleSchool.update');
    Route::delete('littleSchool/{id}', [LittleSchoolController::class, 'destroy'])->name('admin.littleSchool.destroy');
    Route::get('/exportar-children', [LittleSchoolController::class, 'exportChildren'])->name('admin.export.children');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
});
require __DIR__.'/auth.php';
