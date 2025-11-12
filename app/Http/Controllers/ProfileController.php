<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Storage; // Garante que o Storage está importado

class ProfileController extends Controller
{
    /**
     * Exibe o perfil público de um usuário.
     */
    public function show(User $user)
    {
        $user->load(['posts' => function ($query) {
            $query->latest();
        }]);

        return view('profile.show', compact('user'));
    }

    /**
     * Segue ou deixa de seguir um usuário.
     */
    public function toggleFollow(User $user)
    {
        Auth::user()->following()->toggle($user->id);
        return back();
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     * MÉTODO CORRIGIDO
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Preenche os dados de texto (nome, email) a partir da validação
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Lógica para lidar com o upload da imagem de perfil (A PARTE CRUCIAL)
        if ($request->hasFile('image_profile')) {
            // Apaga a imagem antiga se ela existir
            if ($user->image_profile) {
                Storage::disk('public')->delete($user->image_profile);
            }
            // ESTA É A LINHA QUE CORRIGE TUDO:
            // 1. ->file('image_profile') obtém o objeto do ficheiro carregado.
            // 2. ->store('avatars', 'public') guarda o ficheiro em 'storage/app/public/avatars'
            //    e devolve o caminho relativo correto (ex: "avatars/nomeAleatorio.jpg").
            $user->image_profile = $request->file('image_profile')->store('avatars', 'public');
        }

        // Salva todas as alterações (imagem, nome, email) de uma só vez
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}