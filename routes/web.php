<?php

use App\Controllers\HomeController;
use Tidy\Http\Route;
Route::get('/' , function() {
     return view('home');
});


