<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Show Post Form
     */
    public function showCreateForm() {
        return view('create-post');
    }

    /**
     * Create Post
     */
    public function storeNewPost(Request $request){

        // Validate + Add User's ID
        $incoming = $request->validate([
            'title' => 'required',
            'body'  => 'required' 
        ]);
        $incoming['user_id'] = auth()->id(); 

        // Strip Tags
        $incoming['title'] = strip_tags($incoming['title']);
        $incoming['body'] = strip_tags($incoming['body']);

        // Save to DB on table "posts"
        Post::create($incoming);

        return "Hey from POST create-post";
    }



// END
}
