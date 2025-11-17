<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment; // <-- Verifique se o nome do seu modelo é esse
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Comment::truncate();
        Schema::enableForeignKeyConstraints();

        $posts = Post::all();
        $users = User::all();

        if ($posts->isEmpty() || $users->isEmpty()) {
            $this->command->warn("Sem posts ou usuários para criar comentários. Pulando CommentSeeder.");
            return;
        }

        // Lista de comentários exagerados para reagir às "Fake News"
        $commentList = [
            'Eu sempre soube! Finalmente alguém teve a coragem de falar a verdade!',
            'Que absurdo. De onde esse "repórter" tirou isso? Do grupo da família?',
            'Fonte: vozes da minha cabeça.',
            'Isso explica TUDO. Minha vida inteira foi uma mentira.',
            'Fake news clara. O Editor-Chefe devia ter vergonha de aprovar isso.',
            'Alguém sabe se vai chover amanhã? Isso aí é muito complicado pra mim.',
            'Onde eu compro? Manda o link! É pra um amigo.',
            'Li o título e já vim correndo pros comentários. Que loucura!',
            'Meu Deus, e agora? O que faremos? Estou em pânico!',
            'Apaga que dá tempo. Que reportagem lixo.',
            'Isso é uma conspiração dos alienígenas que controlam o preço do pão de queijo!',
            'O "Estagiário do Cafezinho (CV)" escreveria uma matéria melhor.',
            'É por isso que eu só confio nas notícias do "Editor Bairro A (PCC)".',
            'Parei de ler em "Cientistas de Taubaté".',
            'Isso não faz o menor sentido. A Terra claramente tem formato de coxinha.',
            'Manda PIX que eu conto a verdade por trás dessa história.',
            'Meu cunhado que trabalha na prefeitura disse que é verdade. Estou chocado.',
            'Vocês estão a serviço de quem pra postar uma mentira dessas? Vendidos!',
        ];

        foreach ($posts as $post) {
            // Criar 3 a 5 comentários principais por post
            $numberOfComments = rand(3, 5);

            for ($i = 0; $i < $numberOfComments; $i++) {
                Comment::create([
                    'content' => $commentList[array_rand($commentList)], // Coluna 'content'
                    'user_id' => $users->random()->id,
                    'post_id' => $post->id,
                    // 'parent_id' => null, // <-- LINHA REMOVIDA
                ]);
            }

            // --- LÓGICA DE RESPOSTAS FOI TOTALMENTE REMOVIDA ---
            // (Seu banco de dados não suporta comentários aninhados)
        }
    }
}