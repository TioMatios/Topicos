<x-app-layout>
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Resultados da busca por: "{{ $query }}"</h2>
    </div>

    @if($communities->count())
        <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
            <h3 class="font-bold mb-2">Comunidades</h3>
            <ul>
                @foreach ($communities as $community)
                    <li class="py-2 border-b last:border-0"><a href="{{ route('communities.show', $community) }}" class="text-blue-500 hover:underline">r/{{ $community->name }}</a></li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($users->count())
        <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
            <h3 class="font-bold mb-2">Usuários</h3>
            <ul>
                @foreach ($users as $user)
                    <li class="py-2 border-b last:border-0"><a href="{{ route('profile.show', $user) }}" class="text-blue-500 hover:underline">{{ $user->name }}</a></li>
                @endforeach
            </ul>
        </div>
    @endif

    <h3 class="font-bold mb-2">Posts</h3>
    @forelse ($posts as $post)
        <x-post-card :post="$post" />
    @empty
        <div class="bg-white p-4 rounded-lg shadow-sm text-center text-gray-500">
            <p>Nenhum post encontrado com este termo.</p>
        </div>
    @endforelse

</x-app-layout>