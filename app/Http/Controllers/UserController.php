<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Register User
     */
    public function register(Request $request) {

        // Get Incoming Fields and SET Rules
        $incoming = $request->validate([
            "username" => ['required', 'min:4', 'max:12', Rule::unique('users', 'username')],
            "email" => ['required', 'email', Rule::unique('users', 'email')],
            "password" => ['required', 'min: 8', 'confirmed'],
        ]);

        // Hash Password
        $incoming['password'] = bcrypt($incoming['password']);

        // Store them in DB
        User::create($incoming);

        return "You just registered!!";
    }

    /**
     * Login User
     */
    public function login(Request $request) {

        $incoming = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if ( auth()->attempt(['username' => $incoming['loginusername'], 'password' => $incoming['loginpassword']]) ) {
            
            // Regenerate Session
            $request->session()->regenerate();

            return "Congratulations!!";
        } else {
            return "Wrong cridentials!";
        }
    }




// END
}
