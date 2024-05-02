<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function homepage() {
        // Get Data from DB
        $username = "Brad";
        $current_year = date("Y");
        $animals = ["dog", "cat", "squirel"];

        return view("homepage", ['animals'=>$animals, 'name'=>$username, 'year'=>$current_year]);
    }

    public function aboutpage() {
        return view("single-post");
    }
}
