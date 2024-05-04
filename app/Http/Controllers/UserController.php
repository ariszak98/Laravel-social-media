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
        $user = User::create($incoming);

        // Login & Redirect
        auth()->login($user);
        return redirect('/')->with('success', 'Thank you for creating an account.');
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

            return redirect('/')->with('success', 'You have successfully logged in.');
        } else {
            // False Login
            return redirect('/')->with('failure', 'Invalid login.');
        }
    }


    /**
     * Logout User
     */
    public function logout() {
        auth()->logout();
        return redirect('/')->with('success', 'You have successfully logged out.');
    }


    /**
     * Show Correct Home Page
     */
    public function showCorrectHomepage(){

        if (auth()->check()){
            return view('homepage-feed');
        } else {
            return view('homepage');
        }
    }


    /**
     * Show Profile Page
     */
    public function profile(User $user){

        $posts = $user->posts()->latest()->get();
        $count = $user->posts()->count();
        return view('profile-posts', ['username' => $user->username, 'posts' => $posts, 'postsCount'=> $count]);
    }


    /**
     * Show Manage Avatar Form
     */
    public function showAvatarForm(){
        return view('avatar-form');
    }


    /**
     * Store Uploaded Avatar
     */
    public function storeAvatar(Request $request){
        $request->file('avatar')->store('public/avatars');
    }

// END
}
