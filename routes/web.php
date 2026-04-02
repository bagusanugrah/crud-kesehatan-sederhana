<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasienController;

Route::get('/', function () {
    return redirect()->route('pasien.index');
});

Route::resource('pasien', PasienController::class)->except(['create', 'show', 'edit']);
