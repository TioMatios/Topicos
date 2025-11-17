<?php

namespace App\Http\Controllers;

use App\Models\Comment; // Adicionado
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('comments', 'public');
        }

        $post->comments()->create([
            'content' => $validated['content'],
            'image_url' => $imagePath,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Comentário adicionado!');
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update($validated);

        return back()->with('success', 'Comentário atualizado!');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Comentário excluído!');
    }
}