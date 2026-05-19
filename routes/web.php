<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/',[App\Http\Controllers\GeneralController::class, 'allSallesView'])->name('home');

//reservations
Route::get('/reservGenerale',[App\Http\Controllers\GeneralController::class, 'reservGenerale'])->name('reservGenerale');

//sauvegarder une reserv
Route::post('/reservations', [App\Http\Controllers\GeneralController::class, 'storeReservation'])->name('reservations.store');
Route::post('/reservations', [App\Http\Controllers\GeneralController::class, 'storeReservation'])->name('reservations.store');
Route::get('/reservations/{id}/json', [App\Http\Controllers\GeneralController::class, 'reservationJson'])->name('reservations.json');

// salle endpoints: info + calendar events (used by modal/calendar)
Route::middleware(['auth'])->group(function () {
Route::get('/salles/{id}/json', [App\Http\Controllers\GeneralController::class, 'salleJson'])->name('salles.json');
Route::get('/salles/{id}/calendar', [App\Http\Controllers\GeneralController::class, 'salleCalendar'])->name('salles.calendar');
Route::post('/reservations/{id}/approve', [App\Http\Controllers\GeneralController::class, 'approveReservation'])->name('reservations.approve');
Route::get('/reservations/{id}', [App\Http\Controllers\GeneralController::class, 'showReservation'])->name('reservations.show'); // optionnel page complète
Route::delete('/reservations/{id}', [App\Http\Controllers\GeneralController::class, 'deleteReservation'])->name('reservations.destroy');
});
//voir les reservations en tant qu'unconnected
Route::get('/my-reservationsForm', [App\Http\Controllers\GeneralController::class, 'myReservationsForm'])->name('reservations.form');
Route::post('/my-reservations', [App\Http\Controllers\GeneralController::class, 'searchReservations'])->name('reservations.search');

//gestion du saving des docs
Route::post('/docTelechargement',[App\Http\Controllers\GeneralController::class, 'store'])->name('docTelechargement');

//deconnexion
Route::post('/logout',[App\Http\Controllers\logs::class, 'logout'])->name('logout');

//connexion
Route::post('/login',[App\Http\Controllers\logs::class, 'loginAdmin'])->name('login');
Route::get('/login',[App\Http\Controllers\logs::class, 'showloginAdmin'])->name('login');

//inscription d'userss
Route::post('/register',[App\Http\Controllers\logs::class, 'register'])->name('register');
Route::get('/register',[App\Http\Controllers\logs::class, 'showregister'])->name('register');
Route::delete('/users/{id}', [App\Http\Controllers\logs::class, 'destroy'])->name('users.destroy');

//route entreprises

Route::middleware(['auth'])->group(function () {
    Route::get('/adminView',[App\Http\Controllers\GeneralController::class, 'adminView'])->name('adminView');
    //route salles
    Route::get('/allSallesView',[App\Http\Controllers\GeneralController::class, 'allSallesView'])->name('allSallesView');
    //route reservations
    Route::get('/allReservationsView',[App\Http\Controllers\GeneralController::class, 'allReservationsView'])->name('allReservationsView');
    //route utilisateurs
    Route::get('/allUsersView',[App\Http\Controllers\GeneralController::class, 'allUsersView'])->name('allUsersView');

    //route ajout pays
    Route::get('/addPays', [App\Http\Controllers\paysController::class, 'create'])->name('pays.create');
    Route::post('/addPays', [App\Http\Controllers\paysController::class, 'store'])->name('pays.store');
    Route::delete('/pays/{id}', [App\Http\Controllers\paysController::class, 'destroy'])->name('pays.destroy');

    Route::get('/addVille', [App\Http\Controllers\villeController::class, 'create'])->name('ville.create');
    Route::get('/ville/{id}/edit', [App\Http\Controllers\villeController::class, 'edit'])->name('ville.edit');
    Route::post('/addVille', [App\Http\Controllers\villeController::class, 'store'])->name('ville.store');
    Route::put('/ville/{id}/update', [App\Http\Controllers\villeController::class, 'update'])->name('ville.update');
    Route::delete('/ville/{id}', [App\Http\Controllers\villeController::class, 'destroy'])->name('ville.destroy');

    Route::get('/salle/{id}/edit', [App\Http\Controllers\salleController::class, 'edit'])->name('salle.edit');
    Route::put('/salle/{id}/update', [App\Http\Controllers\salleController::class, 'update'])->name('salle.update');
    Route::delete('/salle/{id}', [App\Http\Controllers\salleController::class, 'destroy'])->name('salle.destroy');
});

// Gestion des rôles et permissions (admin only)
Route::middleware(['auth'])->group(function () {
    Route::middleware('role:Admin')->group(function () {
        Route::resource('admin/roles', App\Http\Controllers\Admin\RolePermissionController::class)->only(['index', 'edit', 'update']);
    });
});