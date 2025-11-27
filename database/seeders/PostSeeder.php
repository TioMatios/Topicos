<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Community;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str; // <-- Adicionado para gerar slugs

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Post::truncate();
        Schema::enableForeignKeyConstraints();

        $users = User::all();
        $communities = Community::all();

        if ($users->isEmpty() || $communities->isEmpty()) {
            // Precisa de usuários E comunidades para criar posts
            $this->command->warn("Usuários ou Comunidades não encontrados. Pulando PostSeeder.");
            return;
        }

        // Para cada comunidade, cria 5 posts temáticos (agora como "fake news")
        foreach ($communities as $community) {
            
            // 1. Tenta pegar usuários específicos para esta comunidade (os "repórteres da editoria")
            // Ex: Se o nome da comunidade for "PCC", pega usuários com 'pcc' no email
            $factionKey = $this->getFactionKeyFromName($community->name);
            
            $factionUsers = $users->filter(function ($user) use ($factionKey) {
                // Tenta encontrar emails como 'lider.pcc@exemplo.com' ou 'membro.bnc@exemplo.com'
                return str_contains($user->email, '.' . $factionKey . '@exemplo.com') ||
                       str_contains($user->email, $factionKey . '@exemplo.com');
            });

            // Se não achar repórteres específicos da editoria, usa todos os repórteres
            $userPool = $factionUsers->isNotEmpty() ? $factionUsers : $users;

            // 2. Cria 5 posts para esta comunidade
            for ($i = 0; $i < 5; $i++) {
                $title = $this->getThematicTitle($community->name, $i); // Pega um título de "fake news"
                
                Post::create([
                    'title' => $title,
                    // Adiciona ID da comunidade e índice para garantir slug único
                    'slug' => Str::slug($title) . '-' . $community->id . '-' . $i, 
                    'content' => $this->getThematicContent($community->name), // <-- Conteúdo "fake news"
                    'user_id' => $userPool->random()->id, // Repórter (da editoria ou aleatório)
                    'community_id' => $community->id, // Associa o post à comunidade/editoria
                ]);
            }
        }

        // Cria também 10 posts aleatórios sem comunidade (Notícias Gerais / Clickbait)
        for ($i = 0; $i < 10; $i++) {
            $title = $this->getThematicTitle('default', $i); // Pega um título clickbait
            Post::create([
                'title' => $title,
                'slug' => Str::slug($title) . '-' . Str::random(4), // Adiciona random para slug único
                'content' => $this->getThematicContent('default'), // <-- Conteúdo genérico em PT-BR
                'user_id' => $users->random()->id,
                'community_id' => null,
            ]);
        }
    }

    /**
     * Helper para pegar a chave da facção (baseado nos emails do UserSeeder)
     */
    private function getFactionKeyFromName(string $name): string
    {
        $slug = Str::slug($name);
        if (str_contains($slug, 'pcc')) return 'PCC';
        if (str_contains($slug, 'cv') || str_contains($slug, 'comando-vermelho')) return 'CV';
        if (str_contains($slug, 'tcp')) return 'TCP';
        if (str_contains($slug, 'ada') || str_contains($slug, 'amigos')) return 'ADA';
        if (str_contains($slug, 'manos')) return 'Os Manos';
        if (str_contains($slug, 'bnc') || str_contains($slug, 'bala-na-cara')) return 'Bala na Cara';
        if (str_contains($slug, 'anti-bala')) return 'Anti-Bala';
        return 'default'; // Fallback
    }

    /**
     * Helper para gerar títulos temáticos de "Fake News"
     */
    private function getThematicTitle(string $communityName, int $index): string
    {
        $themes = [
            'PCC' => [
                'EXCLUSIVO: PCC Anuncia Compra do Banco Central com Dinheiro de Rifa!',
                'VAZOU: Plano do PCC para "Pintar SP de Cinza" é Revelado por Ex-Membro',
                'PCC agora exige "pedágio" para quem usar o PIX?, Entenda o boato.',
                'Liderança do PCC estaria financiando filme em Hollywood, diz fonte',
                'Economista do PCC sugere "imposto sobre vacilo" para novos membros',
            ],
            'CV' => [
                'URGENTE: CV Descobre Túnel Secreto para o Japão debaixo do Morro',
                'CV Anuncia Patrocínio Master em Time da Série A do Brasileirão',
                'Morro controlado pelo CV será o primeiro "100% Vegano" do Rio?',
                'Baile Funk do CV quebrou sismógrafo em 3 estados',
                'CV planeja "teleférico de ouro" ligando todas as comunidades',
            ],
            'TCP' => [
                'TCP Anuncia Parceria com a NASA para "Controlar o Clima" no RJ',
                'Terceiro Comando Puro está desenvolvendo app de delivery próprio?',
                'Fontes dizem que TCP quer comprar a Ponte Rio-Niterói',
                'TCP cria "criptomoeda própria" e ela já vale mais que o Bitcoin?',
                'Análise: O TCP está por trás da alta no preço do açaí?',
            ],
            'ADA' => [
                'ADA Constrói Sede Flutuante "Offshore" na Baía de Guanabara',
                'Facção ADA estaria "importando" pombos-correio da Europa',
                'Amigos dos Amigos: O plano para criar uma "ilha particular"',
                'Investigamos: O ADA realmente quer criar um time de polo aquático?',
                'Festa da ADA terá show de holograma de Michael Jackson, dizem boatos',
            ],
            'Bala na Cara' => [
                'BNC Patenteia "Bala Quadrada" para "Dobrar o Impacto", diz especialista',
                'Exclusivo: BNC estaria treinando capivaras para patrulha na orla',
                'Bala na Cara planeja "Dia do Barulho" para protestar contra o silêncio',
                'Físicos quânticos analisam tática do BNC: "É caótico, mas funciona"',
                'BNC proíbe uso da cor "azul claro" em seus territórios. Entenda.',
            ],
            'Os Manos' => [
                'Crise do Chimarrão: Os Manos compraram todo o estoque de Erva-Mate do RS?',
                'Plano secreto d\'Os Manos para "exportar o frio" gaúcho revelado',
                'Membros d\'Os Manos estariam aprendendo a falar "bah" e "tchê" invertido',
                'Gripe Aviária? Não, "Os Manos" estão criando emas de combate',
                'Sede d\'Os Manos terá "churrasqueira de 100 metros"',
            ],
             'Anti-Bala' => [
                'Anti-Bala Constrói "Muro de Escudos Romanos" em Volta do Território',
                'Aliança Anti-Bala anuncia "Manual de Defesa" inspirado em "300"',
                'Anti-Bala está usando "energia solar" para armas sônicas?',
                'Estrategista da Anti-Bala diz ter zerado "Age of Empires" 50 vezes',
                'Boato: Anti-Bala quer pagar curso de "como desviar de balas"',
            ],
            'default' => [
                'Você não vai acreditar no que encontramos no centro da cidade!',
                'Cientistas de Taubaté provam: Terra é plana e tem formato de pão de queijo',
                'Bilionário compra o Oceano Atlântico e anuncia "privatização" das ondas',
                'Gatos são, na verdade, espiões alienígenas, revela documento vazado',
                'Homem ganha na loteria e gasta tudo em paçoca. Veja o vídeo.',
                'Beber água da chuva cura calvície? Estudo de 30 minutos diz que sim.',
                'Prefeitura planeja trocar semáforos por "anjos da guarda" em cruzamentos',
                'Descoberto novo continente flutuante feito de garrafas PET',
                'Por que respirar está se tornando "opcional"? Entenda a nova trend.',
                'Jacaré é visto andando de skate em SC: "Ele é local", diz morador.',
            ]
        ];

        // Tenta achar um tema.
        foreach ($themes as $key => $titles) {
            if (stripos($communityName, $key) !== false) {
                // Usa o índice para pegar títulos diferentes
                return $titles[$index % count($titles)]; 
            }
        }

        // Fallback para comunidades sem tema (Ex: 'Abertos' ou 'default')
        $defaultPool = $themes['default'];
        return $defaultPool[$index % count($defaultPool)];
    }

    /**
     * Helper para gerar conteúdo temático de "Fake News" em português
     */
    private function getThematicContent(string $communityName): string
    {
        $themes = [
            'PCC' => [
                'Nossa equipe de reportagem investigativa (disfarçada de vendedor de amendoim) ouviu de fontes internas que a diretoria do PCC está cansada da instabilidade econômica. O plano? Comprar o Banco Central e fixar o câmbio. "O dólar a 1 real é uma questão de honra", teria dito um suposto economista da facção. Ações da "Rifa1533" dispararam 2000%.',
                'Um documento ultra-secreto, encontrado em uma lixeira perto da bolsa de valores, revela o "Plano Mestre" do PCC: pintar todos os prédios de São Paulo de cinza-grafite. O objetivo, segundo o documento, é "diminuir a poluição visual" e "ajudar o Batman a se esconder". Urbanistas estão confusos.',
            ],
            'CV' => [
                'Engenheiros da UFRJ estão coçando a cabeça. Um repórter investigativo do nosso jornal descobriu um túnel sob o Morro do Alemão que, segundo cálculos, sai diretamente em um beco em Tóquio, Japão. "A logística do sushi e do temaki melhorou 100%", comemora um gerente local. O preço do Yakisoba despencou na comunidade.',
                'Em um movimento de marketing sem precedentes, a liderança do CV estaria negociando um patrocínio master com um time da Série A. "Queremos nosso nome na camisa. A cor vermelha já combina", disse um suposto diretor de marketing. A FIFA e a CBF ainda não comentaram o boato.',
            ],
            'TCP' => [
                'Em uma conferência de imprensa secreta, realizada via pombo-correio, o TCP anunciou uma aliança estratégica com a NASA. O objetivo é usar satélites para "controlar o clima" do Rio de Janeiro. "Queremos garantir sol nos fins de semana de baile e chuva forte quando os rivais tentarem invadir", diz o comunicado, escrito em papel de pão.',
                'Cansados de aplicativos de delivery que cobram taxas abusivas, o TCP estaria desenvolvendo o "TCP-Food". O app promete entregas em 5 minutos em qualquer ponto da cidade, "usando rotas que só a gente conhece". Entregadores de outras empresas estão preocupados.',
            ],
            'ADA' => [
                'Nossa equipe de reportagem aérea (usando um drone de brinquedo) avistou uma estrutura bizarra flutuando na Baía de Guanabara. Fontes dizem que é a nova "sede offshore" da facção Amigos dos Amigos. "É para evitar o trânsito da Avenida Brasil e ter uma vista melhor para o pôr do sol", disse um suposto arquiteto.',
                'A crise no correio tradicional abriu uma nova oportunidade de mercado. O ADA estaria importando pombos-correio de raça da Bélgica, treinados para desviar de linhas de pipa e entregar "encomendas especiais" com 100% de sigilo. "É o iFood 1.0", brinca um especialista em logística.',
            ],
            'Bala na Cara' => [
                'Nosso laboratório de balística investiga o rumor que abalou o mundo da física: o BNC teria criado e patenteado uma "bala quadrada". "É geometricamente impossível, mas eles parecem ter conseguido. O impacto deve ser o dobro", diz um especialista que pediu para não ser identificado. A indústria de coletes à prova de balas está em pânico.',
                'Moradores da orla de Ipanema relataram avistamentos bizarros: capivaras usando pequenos coletes estariam fazendo a patrulha da praia. Fontes indicam que o BNC estaria treinando os animais. "Elas são mais rápidas na areia do que se imagina", diz um surfista.',
            ],
            'Os Manos' => [
                'Atenção, gaúchos! Uma crise do chimarrão pode estar a caminho. Nossos repórteres da editoria "Economia" descobriram que a facção "Os Manos" comprou 98% de todo o estoque de erva-mate do Rio Grande do Sul e de Santa Catarina. O objetivo é monopolizar o mercado. O preço da cuia já subiu 300%.',
                'Um plano audacioso foi descoberto: "Os Manos" estariam tentando engarrafar o "vento minuano" para exportar o frio gaúcho para o Nordeste. "É uma questão de justiça climática", diz um suposto meteorologista envolvido no projeto.',
            ],
            'Anti-Bala' => [
                'Inspirados pelo filme "300" e por táticas do Império Romano, a aliança Anti-Bala estaria construindo uma muralha defensiva usando escudos de tropa de choque. A formação "Testudo" (tartaruga) tem sido vista em treinamentos. "É impenetrável", garante um estrategista local que diz ter zerado Age of Empires 50 vezes.',
                'Em vez de investir em armamento, o Anti-Bala estaria focando em defesa. O boato da vez é que a facção está patrocinando um curso intensivo de "como desviar de balas", ministrado por um monge tibetano e um dublê de Hollywood. As vagas esgotaram em 2 minutos.',
            ],
            'default' => [
                'Nossa equipe foi investigar um boato sobre um jacaré no bueiro e descobriu algo 10x mais bizarro. A prefeitura nega, mas as fotos são chocantes. A verdade é que o jacaré estava usando um chapéu. Veja a galeria de fotos e tire suas próprias conclusões.',
                'Um estudo financiado por uma padaria local e conduzido por cientistas da Universidade de Taubaté concluiu: a Terra não é redonda, nem plana. Ela tem o formato exato de um pão de queijo. "As bordas são crocantes e o centro é macio e quente. Isso explica tudo", disse o Dr. Marins, líder da pesquisa.',
                'Um magnata da tecnologia, que não quis se identificar, anunciou a compra do Oceano Atlântico. Ele planeja "privatizar" as ondas e cobrar um "pedágio de onda" de surfistas. Ele também planeja instalar um zíper gigante para "fechar o oceano" durante o inverno e economizar no aquecimento global.',
                'O documento vazado "Operação Ronronar" detalha como os gatos domésticos são, na verdade, espiões de uma raça alienígena avançada. Eles transmitem dados sobre o consumo de Whiskas para o planeta-mãe. "O plano deles é dominar o estoque de atum", diz o especialista. Cuidado ao fazer carinho.',
            ]
        ];

        // Tenta achar um tema
        foreach ($themes as $key => $paragraphs) {
            if (stripos($communityName, $key) !== false) {
                // Retorna um parágrafo aleatório do tema
                return $paragraphs[array_rand($paragraphs)]; 
            }
        }

        // Fallback para comunidades sem tema (Ex: 'Abertos' ou 'default')
        $defaultPool = $themes['default'];
        return $defaultPool[array_rand($defaultPool)]; 
    }
}