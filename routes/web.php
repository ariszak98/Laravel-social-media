<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExampleController;


/**
 *  WEB ROUTES
 */

Route::get('/', [ExampleController::class, "homepage"]);
Route::get('/about', [ExampleController::class, "aboutpage"]);