<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Criar Novo Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Seletor de Comunidade -->
                        <div>
                            <x-input-label for="community_id" :value="__('Publicar em (Opcional)')" />
                            <select name="community_id" id="community_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Nenhuma (Geral)</option>
                                @foreach ($communities as $community)
                                    <option value="{{ $community->id }}" {{ old('community_id') == $community->id ? 'selected' : '' }}>
                                        {{ $community->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('community_id')" class="mt-2" />
                        </div>

                        <!-- Título -->
                        <div class="mt-4">
                            <x-input-label for="title" :value="__('Título')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Conteúdo -->
                        <div class="mt-4">
                            <x-input-label for="content" :value="__('Conteúdo')" />
                            <textarea name="content" id="content" rows="6" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <!-- Imagem -->
                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Imagem (Opcional)')" />
                            <input id="image" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" type="file" name="image">
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Publicar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>