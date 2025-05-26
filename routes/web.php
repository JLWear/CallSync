<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivreOrController;

Route::get('/livreor', [LivreOrController::class, 'index'])->name('livreor.index');
Route::post('/livreor', [LivreOrController::class, 'store'])->name('livreor.store');

