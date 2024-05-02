<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "<h1>Home Page</h1>
    <hr>
    <p>Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem<br>
    lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum 
    lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum </p>
    <hr>
    <a href='/about'>Goto About page</a>";
    #return view('welcome');
});

Route::get('/about', function () {
    return "<h1>About Page</h1>
    <hr>
    <p>Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem<br>
    lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum 
    lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum </p>
    <hr>
    <a href='/'>Back to Home</a>";
});