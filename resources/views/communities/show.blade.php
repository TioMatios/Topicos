

<x-app-layout>
    {{-- Injetando a Barra Lateral Direita com as informações da comunidade --}}
    <x-slot name="rightSidebar">
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <h3 class="font-bold text-sm flex items-center">
                <img src="{{ $community->icon_url ? asset('storage/' . $community->icon_url) : 'https://ui-avatars.com/api/?name='.urlencode($community->name).'&color=7F9CF5&background=EBF4FF' }}" class="w-6 h-6 rounded-full mr-2">
                <span>Sobre r/{{ $community->name }}</span>
            </h3>
            <p class="text-sm text-gray-700 mt-4">{{ $community->description }}</p>
            <hr class="my-4">
            <div class="flex items-center text-sm">
                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Criada em {{ $community->created_at->format('d M, Y') }}</span>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="text-2xl font-bold">{{ $community->members->count() }}</p>
                <p class="text-xs text-gray-500">Membros</p>
            </div>
            <hr class="my-4">
            <form action="{{ route('communities.toggleJoin', $community) }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-white font-bold py-2 px-4 rounded-full
                    @if(Auth::user()->joinedCommunities->contains($community))
                        bg-gray-500 hover:bg-gray-600
                    @else
                        bg-blue-500 hover:bg-blue-600
                    @endif
                ">
                    {{ Auth::user()->joinedCommunities->contains($community) ? 'Deixar Comunidade' : 'Unir-se' }}
                </button>
            </form>

            <!-- AÇÕES DE MODERAÇÃO (CÓDIGO CORRIGIDO) -->
            {{-- Só mostra o título "Ações de Moderação" se o usuário puder fazer pelo menos uma das ações --}}
            @if(Auth::user()->can('update', $community) || Auth::user()->can('delete', $community))
                <div class="mt-4 pt-4 border-t">
                    <h4 class="font-bold text-xs uppercase text-gray-500 mb-2">Ações de Moderação</h4>
                    <div class="space-y-2">
                        {{-- O botão de editar só aparece se o usuário puder editar --}}
                        @can('update', $community)
                            <a href="{{ route('communities.edit', $community) }}" class="block w-full text-center bg-yellow-500 text-white font-bold py-2 px-4 rounded-full hover:bg-yellow-600 text-sm">
                                Editar Comunidade
                            </a>
                        @endcan
                        {{-- O botão de apagar só aparece se o usuário puder apagar --}}
                        @can('delete', $community)
                            <form action="{{ route('communities.destroy', $community) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja apagar esta comunidade? Esta ação não pode ser desfeita.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-500 text-white font-bold py-2 px-4 rounded-full hover:bg-red-600 text-sm">
                                    Apagar Comunidade
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            @endif
        </div>
    </x-slot>

    {{-- O resto do seu código da view (banner, lista de posts, etc.) continua igual --}}
    <div class="bg-white rounded-lg shadow-sm mb-4">
        <div class="h-32 bg-cover bg-center" style="background-image: url('{{ $community->banner_url ?? 'https://source.unsplash.com/1200x400/?community' }}')"></div>
        <div class="p-4 flex items-center">
            <img class="h-16 w-16 rounded-full border-4 border-white -mt-10" src="{{ $community->icon_url ? asset('storage/' . $community->icon_url) : 'https://ui-avatars.com/api/?name='.urlencode($community->name).'&color=7F9CF5&background=EBF4FF' }}" alt="Ícone de {{ $community->name }}">
            <div class="ml-4">
                <h1 class="text-2xl font-bold">{{ $community->name }}</h1>
                <p class="text-sm text-gray-500">r/{{ $community->name }}</p>
            </div>
            <div class="ml-auto">
                <a href="{{ route('posts.create', ['community_id' => $community->id]) }}" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-full hover:bg-blue-600">
                    Postar
                </a>
            </div>
        </div>
    </div>

    @forelse ($posts as $post)
        <x-post-card :post="$post" />
    @empty
        <div class="bg-white p-4 rounded-lg shadow-sm text-center text-gray-500">
            <p>Ainda não há posts nesta comunidade. Seja o primeiro!</p>
        </div>
    @endforelse

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</x-app-layout>