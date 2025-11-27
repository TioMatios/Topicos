<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; 

class PostController extends Controller
{
    public function create()
    {
        $this->authorize('create', Post::class);
        
        $communities = Community::all();
        
        return view('posts.create', compact('communities'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Post::class);

        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'required_without:image|nullable|string',
            'image'     => 'required_without:content|nullable|image|mimes:jpg,jpeg,png,gif|max:4096',
            'community_id' => 'nullable|exists:communities,id', 
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        Auth::user()->posts()->create([
            'title'       => $validated['title'],
            'content'     => $validated['content'],
            'image_url'   => $imagePath,
            'community_id'=> $validated['community_id'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Post criado com sucesso!');
    }

    public function show(Post $post)
    {
        $post->load('comments.user', 'user', 'community'); 
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        $communities = Community::all();

        return view('posts.edit', compact('post', 'communities'));
    }

    
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'community_id' => 'nullable|exists:communities,id',
        ]);
        
        $post->fill($validated);
        $post->save();

        return redirect()->route('posts.show', $post)->with('success', 'Post atualizado com sucesso!');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if ($post->image_url) {
            Storage::disk('public')->delete($post->image_url);
        }

        $post->delete();

        return redirect()->route('dashboard')->with('success', 'Post excluído com sucesso!');
    }

    public function toggleLike(Post $post)
    {
        Auth::user()->likedPosts()->toggle($post->id);
        return back();
    }

    public function togglePin(Post $post)
{
    $this->authorize('pin', $post);

    if ($post->pinned_at) {
        $post->pinned_at = null;
        $message = 'Post desafixado!';
    } else {
        
        $post->pinned_at = now();
        $message = 'Post fixado no topo!';
    }
    
    $post->save();

    return back()->with('success', $message);
}

}
