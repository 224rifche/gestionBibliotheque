<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivreController;
use App\Http\Controllers\EmpruntController;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role === 'Rlecteur') {
            return redirect()->route('catalogue');
        }
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    // Dashboard uniquement pour Bibliothécaires et Administrateurs
    return view('dashboard');
})->middleware(['auth', 'role:Rbibliothecaire,Radmin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'role:Rbibliothecaire,Radmin'])->group(function () {
    // Gestion des livres
    Route::get('/livres', [LivreController::class, 'index'])->name('livres.index');
    Route::get('/livres/create', [LivreController::class, 'create'])->name('livres.create');
    Route::post('/livres', [LivreController::class, 'store'])->name('livres.store');
    Route::get('/livres/{livre}/edit', [LivreController::class, 'edit'])->name('livres.edit');
    Route::put('/livres/{livre}', [LivreController::class, 'update'])->name('livres.update');
    Route::patch('/livres/{livre}/indisponible', [LivreController::class, 'indisponible'])->name('livres.indisponible');
    
    // Gestion des catégories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [\App\Http\Controllers\CategorieController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\CategorieController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\CategorieController::class, 'store'])->name('store');
        Route::get('/{categorie}/edit', [\App\Http\Controllers\CategorieController::class, 'edit'])->name('edit');
        Route::put('/{categorie}', [\App\Http\Controllers\CategorieController::class, 'update'])->name('update');
        Route::delete('/{categorie}', [\App\Http\Controllers\CategorieController::class, 'destroy'])->name('destroy');
    });
    
    // Gestion des auteurs
    Route::prefix('auteurs')->name('auteurs.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AuteurController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\AuteurController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\AuteurController::class, 'store'])->name('store');
        Route::get('/{auteur}/edit', [\App\Http\Controllers\AuteurController::class, 'edit'])->name('edit');
        Route::put('/{auteur}', [\App\Http\Controllers\AuteurController::class, 'update'])->name('update');
        Route::delete('/{auteur}', [\App\Http\Controllers\AuteurController::class, 'destroy'])->name('destroy');
    });
});

Route::get('/catalogue', [LivreController::class, 'catalogue'])
    ->middleware('auth')
    ->name('catalogue');

Route::middleware(['auth', 'role:Rlecteur'])->group(function () {
    Route::post('/emprunts/demander/{livre}', [EmpruntController::class, 'demander'])->name('emprunts.demander');
    Route::get('/mes-emprunts', [EmpruntController::class, 'mesEmprunts'])->name('emprunts.mes');
});

Route::middleware(['auth', 'role:Rbibliothecaire,Radmin'])->prefix('emprunts')->name('emprunts.')->group(function () {
    Route::get('/demandes', [EmpruntController::class, 'demandes'])->name('demandes');
    Route::post('/{emprunt}/valider', [EmpruntController::class, 'valider'])->name('valider');
    Route::get('/en-cours', [EmpruntController::class, 'enCours'])->name('en_cours');
    Route::post('/{emprunt}/retour', [EmpruntController::class, 'validerRetour'])->name('retour');
    Route::get('/retards', [EmpruntController::class, 'retards'])->name('retards');
});

// Gestion des utilisateurs (Admin uniquement)
Route::middleware(['auth', 'role:Radmin'])->prefix('users')->name('users.')->group(function () {
    Route::get('/', [\App\Http\Controllers\UserController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\UserController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\UserController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
});


require __DIR__.'/auth.php';
