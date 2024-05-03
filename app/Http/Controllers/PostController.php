<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
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

        // Save to DB on table "posts" + save the new Post instance
        $post = Post::create($incoming);

        return redirect("/post/{$post->id}")->with('success', "New post successfully created.");
    }

    /**
     * Show Single Post
     */
    public function viewSinglePost(Post $post){

        // Convert Markdown Language + Override body with new one
        $md_body = strip_tags(Str::markdown($post->body), '<p><b><strong><i><ol><ul><li><em><h3><br>');
        $post->body = $md_body;
        
        return view('single-post', ['post'=>$post]); 
    }

// END
}
