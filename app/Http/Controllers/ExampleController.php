<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function homepage() {
        return "<h1>Home Page</h1><hr><a href='/about'>Goto About page</a>";
    }

    public function aboutpage() {
        return "<h1>About Page</h1><hr><a href='/'>Back to Home</a>";
    }
}
