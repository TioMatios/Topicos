<?php

namespace App\Http\Controllers;

use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Validation\Rules; 

class UserController extends Controller
{
    
    public function create()
    {
       
        return view('admin.users.create');
    }

    
    public function store(Request $request)
    {
       
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
        ]);

        
        return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso!');
    }

    
    public function index()
    {
        $users = User::latest()->paginate(10); 
        return view('users.index', compact('users'));
    }
}