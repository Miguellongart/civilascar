<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', [FrontController::class, 'home'])->name('front.home');
Route::get('/contacto', [FrontController::class, 'Contact'])->name('front.contact');
Route::get('/sobre-nosotros', [FrontController::class, 'About'])->name('front.about');
Route::get('/escuelita', [FrontController::class, 'LittleSchool'])->name('front.school');
Route::get('/liga-cafetera', [FrontController::class, 'tournament'])->name('front.tournament');
// Route::get('/contacto', [FrontController::class, 'Contact'])->name('front.contact');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
});
require __DIR__.'/auth.php';
