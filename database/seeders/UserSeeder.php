<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Desliga a verificação de chaves estrangeiras
        Schema::disableForeignKeyConstraints();

        // 2. Limpa a tabela de usuários de forma segura
        User::truncate();

        // 3. Liga a verificação de chaves estrangeiras novamente
        Schema::enableForeignKeyConstraints();

        // Senha padrão para todos os usuários
        $password = Hash::make('12345678');

        // --- Redação PCC (Primeiro Comando da Capital) ---

        // Editor-Chefe (Admin)
        User::create([
            'name' => 'Editor-Chefe (PCC)',
            'email' => 'lider.pcc@exemplo.com', // Email mantido para linkar com CommunitySeeder
            'password' => $password,
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Editor de Seção (Admin2)
        User::create([
            'name' => 'Editor Bairro A (PCC)',
            'email' => 'gerente.pcc.a@exemplo.com', // Email mantido
            'password' => $password,
            'role' => 'admin2',
            'email_verified_at' => now(),
        ]);

        // Repórter (User)
        User::create([
            'name' => 'Repórter Investigativo (PCC)',
            'email' => 'soldado.pcc@exemplo.com', // Email mantido
            'password' => $password,
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // --- Redação CV (Comando Vermelho) ---

        // Editor-Chefe (Admin)
        User::create([
            'name' => 'Editor-Chefe (CV)',
            'email' => 'lider.cv@exemplo.com', // Email mantido
            'password' => $password,
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Editor de Seção (Admin2)
        User::create([
            'name' => 'Editor do Morro (CV)',
            'email' => 'dono.morro.cv@exemplo.com', // Email mantido
            'password' => $password,
            'role' => 'admin2',
            'email_verified_at' => now(),
        ]);

        // Repórter (User)
        User::create([
            'name' => 'Jornalista de Dados (CV)',
            'email' => 'vapor.cv@exemplo.com', // Email mantido
            'password' => $password,
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // Estagiário (User)
        User::create([
            'name' => 'Estagiário do Cafezinho (CV)',
            'email' => 'fogueteiro.cv@exemplo.com', // Email mantido
            'password' => $password,
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // --- Redação TCP (Terceiro Comando Puro) ---

        // Editor-Chefe (Admin)
        User::create([
            'name' => 'Editor-Chefe (TCP)',
            'email' => 'frente.tcp@exemplo.com', // Email mantido
            'password' => $password,
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Repórter (User)
        User::create([
            'name' => 'Repórter de Campo (TCP)',
            'email' => 'membro.tcp@exemplo.com', // Email mantido
            'password' => $password,
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // --- Redação ADA (Amigos dos Amigos) ---

         // Editor-Chefe (Admin)
         User::create([
            'name' => 'Editor-Chefe (ADA)',
            'email' => 'cabeca.ada@exemplo.com', // Email mantido
            'password' => $password,
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Repórter (User)
        User::create([
            'name' => 'Repórter (ADA)',
            'email' => 'membro.ada@exemplo.com', // Email mantido
            'password' => $password,
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // --- Redações RS ---

        // --- Os Manos ---
        User::create([
            'name' => 'Editor-Chefe (Os Manos)',
            'email' => 'lider.manos@exemplo.com', // Email mantido
            'password' => $password,
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Repórter (Os Manos)',
            'email' => 'membro.manos@exemplo.com', // Email mantido
            'password' => $password,
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // --- Bala na Cara (BNC) ---
        User::create([
            'name' => 'Editor-Chefe (BNC)',
            'email' => 'lider.bnc@exemplo.com', // Email mantido
            'password' => $password,
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Repórter (BNC)',
            'email' => 'membro.bnc@exemplo.com', // Email mantido
            'password' => $password,
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // --- Anti-Bala ---
        User::create([
            'name' => 'Editor-Chefe (Anti-Bala)',
            'email' => 'lider.antibala@exemplo.com', // Email mantido
            'password' => $password,
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Repórter (Anti-Bala)',
            'email' => 'membro.antibala@exemplo.com', // Email mantido
            'password' => $password,
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // --- Abertos ---
        User::create([
            'name' => 'Freelancer (Abertos)',
            'email' => 'membro.abertos@exemplo.com', // Email mantido
            'password' => $password,
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }
}