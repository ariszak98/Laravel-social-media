<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{

    /**
     * Create Follow
     * - Cant follow yourself
     * - Cant follow someone twice
     */
    public function createFollow(User $user){
         
        // Cant follow yourself
        if($user->id == auth()->user()->id){
            return back()->with('failure', 'You cant follow yourself.');
        }
        // Cant follow someone twice
        $existCheck = Follow::where([ ['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id] ])->count();
        if($existCheck){
            return back()->with('failure', 'You are already following <b>' . $user->username . '</b>');
        }

        // Create Follow row
        $newFollow = new Follow();
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();

        // Redirect on Success
        return back()->with('success', 'You followed <b>' . $user->username . '</b>');

    }
    

    /**
     * Remove Follow
     * - Cant unfollow yourself
     * - Cant unfollow someone NOT following already
     */
    public function removeFollow(User $user){

        // You cant unfollow yourself
        if ( auth()->user()->id == $user->id ){
            return back()->with('failure', 'Cant do that action on you.');
        }

        $isFollowing = Follow::where([ ['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id] ])->delete();
        
        // Cant unfollow someone NOT following already
        if(!$isFollowing){
            return back()->with('failure', 'You were not following that person.');
        }

        // If all good:
        return back()->with('success', 'User <b>' . $user->username . '</b> successfully unfollowed.');

    }






     
// END
}