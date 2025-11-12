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

    /**
     * Atualiza um comentário existente.
     * NOVO MÉTODO
     */
    public function update(Request $request, Comment $comment)
    {
        // Verifica se o usuário tem permissão para editar usando a CommentPolicy
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update($validated);

        return back()->with('success', 'Comentário atualizado!');
    }

    /**
     * Apaga um comentário.
     * NOVO MÉTODO
     */
    public function destroy(Comment $comment)
    {
        // Verifica se o usuário tem permissão para apagar usando a CommentPolicy
        $this->authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Comentário excluído!');
    }
}