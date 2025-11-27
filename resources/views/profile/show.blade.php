<x-app-layout>
    {{-- Barra Lateral Direita com Informações do Usuário --}}
    <x-slot name="rightSidebar">
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <div class="text-center">
                <img class="h-20 w-20 rounded-full object-cover mx-auto" src="{{ $user->image_profile ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}" alt="{{ $user->name }}">
                
                <div class="mt-4">
                    <h3 class="text-xl font-bold flex items-center justify-center">
                        <span>{{ $user->name }}</span>
                        
                        {{-- BADGE NO PERFIL DO USUÁRIO --}}
                        @if($user->role === 'admin')
                            <span class="ml-2 text-xs font-semibold text-white bg-red-500 px-2 py-0.5 rounded-full">Admin</span>
                        @elseif($user->role === 'admin2')
                            <span class="ml-2 text-xs font-semibold text-white bg-blue-500 px-2 py-0.5 rounded-full">Mod</span>
                        @endif
                    </h3>
                    @if($user->nickname)
                        <p class="text-sm text-gray-500">{{ '@' . $user->nickname }}</p>
                    @endif
                </div>
            </div>
            
            <div class="mt-4 border-t pt-4">
                <h4 class="font-semibold text-xs uppercase text-gray-500">Descrição</h4>
                <p class="text-sm text-gray-700 mt-1">{{ $user->description_profile ?? 'Nenhuma descrição fornecida.' }}</p>
            </div>

            <div class="mt-4 border-t pt-4">
                 <p class="text-sm text-gray-500">Entrou em {{ $user->created_at->format('M Y') }}</p>
            </div>

            @if(Auth::id() !== $user->id)
                <form action="{{ route('profile.toggleFollow', $user) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full text-white font-bold py-2 px-4 rounded-full
                        @if(Auth::user()->following->contains($user))
                            bg-gray-500 hover:bg-gray-600
                        @else
                            bg-blue-500 hover:bg-blue-600
                        @endif
                    ">
                        {{ Auth::user()->following->contains($user) ? 'Deixar de Seguir' : 'Seguir' }}
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <h2 class="text-xl font-bold text-gray-800 mb-4">Posts de {{ $user->name }}</h2>
    @forelse ($user->posts as $post)
        <x-post-card :post="$post" />
    @empty
        <div class="bg-white p-4 rounded-lg shadow-sm text-center text-gray-500">
            <p>{{ $user->name }} ainda não publicou nada.</p>
        </div>
    @endforelse
</x-app-layout>