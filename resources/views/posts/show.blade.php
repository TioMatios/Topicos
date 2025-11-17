<x-app-layout>
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                {{ $post->title }}
            </h1>
            <p class="text-sm text-gray-500 mt-2 flex items-center">
                <span>Postado por </span>
                <a href="{{ route('profile.show', $post->user) }}" class="font-semibold hover:underline ml-1">{{ $post->user->name }}</a>
                
                {{-- BADGE PARA O AUTOR DO POST --}}
                @if($post->user->role === 'admin')
                    <span class="ml-2 text-xs font-semibold text-white bg-red-500 px-2 py-0.5 rounded-full">Admin</span>
                @elseif($post->user->role === 'admin2')
                    <span class="ml-2 text-xs font-semibold text-white bg-blue-500 px-2 py-0.5 rounded-full">Mod</span>
                @endif

                @if ($post->community)
                    <span class="mx-1">•</span>
                    <span>em <a href="{{ route('communities.show', $post->community) }}" class="font-semibold hover:underline">{{ $post->community->name }}</a></span>
                @endif
                <span class="mx-1">•</span>
                <span>{{ $post->created_at->diffForHumans() }}</span>
            </p>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- BOTÕES DE AÇÃO PARA O POST -->
                    <div class="flex items-center space-x-4 mb-4 border-b pb-4">
                        @can('update', $post)
                            <a href="{{ route('posts.edit', $post) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">Editar</a>
                        @endcan
                        @can('delete', $post)
                            <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Tem certeza que deseja apagar este post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Apagar</button>
                            </form>
                        @endcan
                        {{-- BOTÃO DE FIXAR POST --}}
                        @can('pin', $post)
    <form method="POST" action="{{ route('posts.togglePin', $post) }}">
        @csrf
        <button type="submit" class="text-sm font-medium text-green-600 hover:text-green-800">
            {{-- LÓGICA CORRIGIDA --}}
            {{ $post->pinned_at ? 'Desafixar Post' : 'Fixar Post' }}
        </button>
    </form>
@endcan
                    </div>
                    
                    @if ($post->image_url)
                        <img src="{{ asset('storage/' . $post->image_url) }}" alt="Imagem do Post" class="mb-6 rounded-lg w-full">
                    @endif
                    
                    <div class="prose max-w-none">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>
            </div>

            <!-- Seção de Comentários -->
            <div class="mt-8">
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Comentários ({{ $post->comments->count() }})</h3>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                    <form action="{{ route('comments.store', $post) }}" method="POST">
                        @csrf
                        <textarea name="content" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="O que você acha?"></textarea>
                        <x-primary-button class="mt-4">{{ __('Comentar') }}</x-primary-button>
                    </form>
                </div>

                <div class="space-y-4">
                    @forelse ($post->comments->sortByDesc('created_at') as $comment)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                            <div class="flex items-start">
                                <a href="{{ route('profile.show', $comment->user) }}">
                                    <img class="h-10 w-10 rounded-full" src="{{ $comment->user->image_profile ?? 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&color=7F9CF5&background=EBF4FF' }}" alt="{{ $comment->user->name }}">
                                </a>
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center">
                                        <a href="{{ route('profile.show', $comment->user) }}" class="text-sm font-medium text-gray-900 hover:underline">{{ $comment->user->name }}</a>
                                        
                                        {{-- BADGE PARA O AUTOR DO COMENTÁRIO --}}
                                        @if($comment->user->role === 'admin')
                                            <span class="ml-2 text-xs font-semibold text-white bg-red-500 px-2 py-0.5 rounded-full">Admin</span>
                                        @elseif($comment->user->role === 'admin2')
                                            <span class="ml-2 text-xs font-semibold text-white bg-blue-500 px-2 py-0.5 rounded-full">Mod</span>
                                        @endif
                                        
                                        <span class="text-xs text-gray-500 ml-2">• {{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 mt-1">{{ $comment->content }}</p>

                                    <div class="flex items-center space-x-3 mt-2">
                                        @can('delete', $comment)
                                            <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Tem certeza que deseja apagar este comentário?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-700">Apagar</button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                            <p class="text-gray-500">Nenhum comentário ainda.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>