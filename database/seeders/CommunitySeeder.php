<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str; // <-- Adicionado para gerar slugs

class CommunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Community::truncate();
        Schema::enableForeignKeyConstraints();

        // Define as "Editorias" (antigas facções) e seus respectivos Editores-Chefes
        // Os emails devem bater com os criados no UserSeeder.php
        $editorials = [
            [
                'name' => 'Editoria de Economia & Poder',
                'description' => 'Investigações a fundo sobre o mercado financeiro, política e quem realmente comanda o capital. (Antiga PCC)',
                'owner_email' => 'lider.pcc@exemplo.com' // <-- Email mantido
            ],
            [
                'name' => 'Cultura & Cotidiano Urbano',
                'description' => 'As notícias que vêm de dentro da comunidade. Cultura, urbanismo e os segredos dos morros. (Antiga CVRL)',
                'owner_email' => 'lider.cv@exemplo.com' // <-- Email mantido
            ],
            [
                'name' => 'Ciência & Tecnologia Pura',
                'description' => 'Da NASA aos apps de delivery. As inovações (e conspirações) que vão mudar o mundo. (Antiga TCP)',
                'owner_email' => 'frente.tcp@exemplo.com' // <-- Email mantido
            ],
            [
                'name' => 'Sociedade & Círculos Fechados',
                'description' => 'Notícias exclusivas dos "Amigos dos Amigos". O que acontece por trás das portas fechadas. (Antiga ADA)',
                'owner_email' => 'cabeca.ada@exemplo.com' // <-- Email mantido
            ],
            [
                'name' => 'Redação Sul (Agro & Cultura)',
                'description' => 'A firma não para de reportar. As últimas do agronegócio e da cultura do Sul. (Antiga Os Manos)',
                'owner_email' => 'lider.manos@exemplo.com' // <-- Email mantido
            ],
            [
                'name' => 'Reportagens de Impacto (BNC)',
                'description' => 'Notícias que chegam como um tiro. A verdade nua e crua, doa a quem doer. (Antiga BNC)',
                'owner_email' => 'lider.bnc@exemplo.com' // <-- Email mantido
            ],
            [
                'name' => 'Defesa & Checagem (Aliança)',
                'description' => 'A aliança contra as fake news (dos outros). Estratégias de defesa e checagem de fatos. (Antiga Anti-Bala)',
                'owner_email' => 'lider.antibala@exemplo.com' // <-- Email mantido
            ],
            [
                'name' => 'Pautas Abertas (Freelancers)',
                'description' => 'Discussões gerais, pautas sugeridas e o espaço dos nossos repórteres freelancers. (Antiga Abertos)',
                'owner_email' => 'membro.abertos@exemplo.com' // <-- Email mantido
            ]
        ];

        // Itera sobre as "editorias" e cria as comunidades
        foreach ($editorials as $editorial) {
            // 1. Encontra o usuário "dono" (Editor-Chefe)
            $owner = User::where('email', $editorial['owner_email'])->first();

            // 2. Se o usuário dono existir, cria a comunidade
            if ($owner) {
                Community::create([
                    'name' => $editorial['name'],
                    'slug' => Str::slug($editorial['name']), // Gera o slug
                    'description' => $editorial['description'],
                    'user_id' => $owner->id, // Associa ao Editor-Chefe
                ]);
            } else {
                // Opcional: Avisa no console se um líder não for encontrado
                $this->command->warn("Aviso: Usuário '{$editorial['owner_email']}' não encontrado. Pulando comunidade '{$editorial['name']}'.");
            }
        }
    }
}