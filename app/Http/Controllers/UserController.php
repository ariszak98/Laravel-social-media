<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

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
        return view('profile-posts', ['avatar'=> $user->avatar ,'username' => $user->username, 'posts' => $posts, 'postsCount'=> $count]);
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

        $user = auth()->user();

        $filename = $user->id . "-" . uniqid() . ".jpeg";

        // Validate File
        $request->validate([
            'avatar'    =>  'required|image|max:3000'
        ]);

        // Modify File
        $manager = new ImageManager(new Driver());
        $image = $manager->read($request->file('avatar'));
        $imgData = $image->cover(120, 120)->toJpeg();

        // Store File
        Storage::put('public/avatars/' . $filename, $imgData);

        // Ex-Avatar
        $oldAvatar = $user->avatar;

        // Update Database on "avatar" column
        $user->avatar = $filename;
        $user->save();

        // Delete old Avatar
        if ( $oldAvatar != '/fallback-avatar.jpg' ) {
            Storage::delete(str_replace('/storage/', 'public/', $oldAvatar));
        }

        return redirect('/profile/' . $user->username)->with('success', "Avatar successfully changed.");

    }

// END
}
