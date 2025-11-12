<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    <form method="POST" action="{{ route('posts.update', $post) }}">
                        @csrf
                        @method('PUT') <!-- Importante para rotas de atualização -->

                        <!-- Seletor de Comunidade -->
                        <div>
                            <x-input-label for="community_id" :value="__('Publicar em (Opcional)')" />
                            <select name="community_id" id="community_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Nenhuma (Geral)</option>
                                @foreach ($communities as $community)
                                    <option value="{{ $community->id }}" {{ old('community_id', $post->community_id) == $community->id ? 'selected' : '' }}>
                                        {{ $community->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Título -->
                        <div class="mt-4">
                            <x-input-label for="title" :value="__('Título')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $post->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Conteúdo -->
                        <div class="mt-4">
                            <x-input-label for="content" :value="__('Conteúdo')" />
                            <textarea name="content" id="content" rows="6" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('content', $post->content) }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <!-- Imagem Atual -->
                        @if ($post->image_url)
                            <div class="mt-4">
                                <x-input-label :value="__('Imagem Atual')" />
                                <img src="{{ asset('storage/' . $post->image_url) }}" alt="Imagem do Post" class="mt-2 rounded-lg max-w-sm">
                            </div>
                        @endif

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('posts.show', $post) }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
                            <x-primary-button>
                                {{ __('Salvar Alterações') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>