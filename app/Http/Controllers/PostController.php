<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller

{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }

    public function index()
    {
        $posts = Post::with('user')
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    public function update(Request $request, Post $post)
    {
        // Manual Authorization: Only the owner can update
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $post->update($request->only('title', 'content'));

        return redirect()->route('posts.index')->with('success', 'Post updated successfully');
    }

    public function destroy(Post $post)
    {
        // Manual Authorization: Only the owner can delete
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully');
    }
}