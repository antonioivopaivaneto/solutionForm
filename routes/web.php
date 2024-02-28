<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SolicitacaoController;
use App\Models\Solicitacao;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Solicitacao', [

    ]);
});

Route::get('/historico',[DashboardController::class,'Historico'])->middleware(['auth', 'verified'])->name('historico');
Route::get('/dashboard',[DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/concluirSolicitacao/{id}',[SolicitacaoController::class,'concluirSolicitacao'])->middleware(['auth', 'verified'])->name('concluirSolicitacao');
Route::get('/reabrirSolicitacao/{id}',[SolicitacaoController::class,'reabrirSolicitacao'])->middleware(['auth', 'verified'])->name('reabrirSolicitacao');

Route::get('/uploads/{filename}',[SolicitacaoController::class,'showImage'])->name('image.show');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('solicitacao', SolicitacaoController::class);

require __DIR__.'/auth.php';
