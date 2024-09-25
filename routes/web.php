<?php

use App\Http\Controllers\mainController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('formulaire');
});


