

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Comunidades') }}
            </h2>
            
            {{-- MUDANÇA: Usando a diretiva @can, que é mais limpa que o @if --}}
            @can('create', App\Models\Community::class)
                {{-- CORREÇÃO: Voltando a usar seu link <a> original. 
                     Ele já estava funcionando e estilizado corretamente. --}}
                <a href="{{ route('communities.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Criar Nova Comunidade
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="space-y-4">
                        {{-- CORREÇÃO: Voltando à sua listagem original, sem o ícone ou Str::limit,
                             para garantir que nada quebre. --}}
                        @forelse ($communities as $community)
                            <div class="p-4 border rounded-md hover:bg-gray-50">
                                <a href="{{ route('communities.show', $community) }}" class="block">
                                    <h3 class="font-bold text-lg text-blue-600 hover:underline">{{ $community->name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $community->description }}</p>
                                    <p class="text-xs text-gray-500 mt-2">{{ $community->members->count() }} Membros</p>
                                </a>
                            </div>
                        @empty
                            <p>Nenhuma comunidade encontrada.</p>
                        @endforelse
                    </div>

                    @if ($communities->hasPages())
                        <div class="mt-6">
                            {{ $communities->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>