<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{

    /**
     * Search for a Post
     */
    public function search($term) {
        $posts = Post::search($term)->get();
        $posts->load('user:id,username,avatar');
        return $posts;
    }

    /**
     * Show Post EDIT Form
     */
    public function showEditForm(Post $post){

        return view('edit-post', ['post' => $post]);
    }

    /**
     * Update a Post
     */
    public function actuallyUpdatePost(Post $post, Request $request){

        // Validate new Post data
        $incoming = $request->validate([
            'title' => 'required',
            'body'  => 'required'
        ]);

        // Strip Tags
        $incoming['title'] = strip_tags($incoming['title']);
        $incoming['body'] = strip_tags($incoming['body']);

        // Update + Redirect to same Post
        $post->update($incoming);
        return back()->with('success', "Post successfully updated.");
    }

    /**
     * Show Post Form
     */
    public function showCreateForm() {

        // Authenticate
        if (!auth()->check()){
            return redirect('/');
        }

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

    /**
     * Delete Post
     */
    public function delete(Post $post) {

         $post->delete();
         return redirect('/profile/' . auth()->user()->username)->with('success', 'Post deleted.');


    }




// END
}
