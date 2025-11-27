<x-app-layout>
    {{-- Bloco "Criar Post" --}}
    @can('create', App\Models\Post::class)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 text-gray-900 flex items-center space-x-4">
                <a href="{{ route('profile.show', Auth::user()) }}">
                    <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->image_profile ? asset('storage/' . Auth::user()->image_profile) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF' }}" alt="{{ Auth::user()->name }}">
                </a>
                <a href="{{ route('posts.create') }}" class="block w-full bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-md py-2 px-4 text-left text-gray-600">
                    Criar um novo post...
                </a>
            </div>
        </div>
    @endcan
    
    {{-- Lista de Posts --}}
    @forelse ($posts as $post) {{-- A linha que estava a dar erro --}}
        <x-post-card :post="$post" />
    @empty
        <div class="bg-white p-4 rounded-lg shadow-sm text-center text-gray-500">
            <p>Ainda não há posts. Siga outros usuários ou crie o seu primeiro post!</p>
        </div>
    @endforelse

    {{-- Paginação --}}
    @if ($posts->hasPages())
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    @endif
    
    {{-- Barra Lateral Direita --}}
    <x-slot name="rightSidebar">
         @if($popularCommunities->isNotEmpty())
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h3 class="font-bold text-sm uppercase tracking-wider text-gray-700 mb-3">Comunidades Populares</h3>
                <div class="space-y-3">
                    @foreach($popularCommunities as $community)
                        <div class="flex items-center">
                            <a href="{{ route('communities.show', $community) }}" class="flex items-center space-x-2 group">
                                <img src="{{ $community->icon_url ? asset('storage/' . $community->icon_url) : 'https://ui-avatars.com/api/?name='.urlencode($community->name).'&color=7F9CF5&background=EBF4FF' }}" class="w-8 h-8 rounded-full">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800 group-hover:underline">r/{{ $community->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $community->members_count }} membros</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
         @endif
    </x-slot>
</x-app-layout>