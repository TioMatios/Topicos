<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Importado para gerenciar arquivos

class PostController extends Controller
{
    /**
     * Mostra o formulário para criar um novo post.
     */
    public function create()
    {
        $this->authorize('create', Post::class);
        
        // Buscamos todas as comunidades para exibi-las no seletor do formulário
        $communities = Community::all();
        
        return view('posts.create', compact('communities'));
    }

    /**
     * Salva um novo post no banco de dados.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Post::class);

        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'required_without:image|nullable|string',
            'image'     => 'required_without:content|nullable|image|mimes:jpg,jpeg,png,gif|max:4096',
            'community_id' => 'nullable|exists:communities,id', // Valida o community_id
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        Auth::user()->posts()->create([
            'title'       => $validated['title'],
            'content'     => $validated['content'],
            'image_url'   => $imagePath,
            'community_id'=> $validated['community_id'], // Salva o ID da comunidade
        ]);

        return redirect()->route('dashboard')->with('success', 'Post criado com sucesso!');
    }

    /**
     * Exibe um único post.
     */
    public function show(Post $post)
    {
        $post->load('comments.user', 'user', 'community'); // Adicionado 'community'
        return view('posts.show', compact('post'));
    }

    /**
     * Mostra o formulário para editar um post existente.
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        // Também precisamos das comunidades aqui para preencher o seletor
        $communities = Community::all();

        return view('posts.edit', compact('post', 'communities'));
    }

    /**
     * Atualiza um post existente no banco de dados.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'community_id' => 'nullable|exists:communities,id',
        ]);
        
        // Preenche os dados validados, incluindo o community_id
        $post->fill($validated);
        $post->save();

        return redirect()->route('posts.show', $post)->with('success', 'Post atualizado com sucesso!');
    }

    /**
     * Remove um post do banco de dados.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        // MELHORIA: Deleta a imagem do armazenamento se ela existir
        if ($post->image_url) {
            Storage::disk('public')->delete($post->image_url);
        }

        $post->delete();

        return redirect()->route('dashboard')->with('success', 'Post excluído com sucesso!');
    }

    /**
     * Adiciona ou remove um like de um post.
     */
    public function toggleLike(Post $post)
    {
        Auth::user()->likedPosts()->toggle($post->id);
        return back();
    }

    public function togglePin(Post $post)
    {
    $this->authorize('pin', $post); // Verifica a permissão primeiro!

    $post->is_pinned = !$post->is_pinned; // Inverte o valor (true vira false, false vira true)
    $post->save();

    return back()->with('success', 'Status de fixação do post alterado!');
    }

}
