<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
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
     * Get Shared Data for the (3) Profile pages :
     * - profile    : posts
     * - followers  : followers
     * - following  : following
     */
    private function getSharedData($user){

        // Check if being followed Already
        $currentlyFollowing = 0;
        if(auth()->check()){
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        }
        $count = $user->posts()->count();

        // Pass Shared Data as Global Blade variable
        View::share('sharedData', ['currentlyFollowing' => $currentlyFollowing,'avatar'=> $user->avatar ,'username' => $user->username, 'postsCount'=> $count]);
    }

    /**
     * Show Profile Page
     */
    public function profile(User $user){
        $this->getSharedData($user);
        return view('profile-posts', ['posts' => $user->posts()->latest()->get()]);
    }


    /**
     * Show Profile Followers
     */
    public function profileFollowers(User $user){
        $this->getSharedData($user);
        return view('profile-followers', ['posts' => $user->posts()->latest()->get()]);
    }

    /**
     * Show Profile Following
     */
    public function profileFollowing(User $user){
        $this->getSharedData($user);
        return view('profile-following', ['posts' => $user->posts()->latest()->get()]);
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
