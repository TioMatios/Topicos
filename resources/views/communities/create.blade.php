

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Criar Nova Comunidade') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- ADICIONADO O ENCTYPE PARA PERMITIR UPLOAD DE ARQUIVOS --}}
                    <form method="POST" action="{{ route('communities.store') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- Nome -->
                        <div>
                            <x-input-label for="name" :value="__('Nome da Comunidade')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Descrição -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Descrição')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- CAMPO DE UPLOAD DE ÍCONE ADICIONADO -->
                        <div class="mt-4">
                            <x-input-label for="icon" :value="__('Ícone da Comunidade (Opcional)')" />
                            <input id="icon" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" type="file" name="icon">
                            <x-input-error :messages="$errors->get('icon')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Criar Comunidade') }}
                            </x-primary-button>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    </div>
</x-app-layout>