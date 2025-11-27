<div class="bg-white p-4 rounded-lg shadow-sm">
    <h3 class="font-bold text-sm uppercase text-gray-500 tracking-wider">Comunidades Populares</h3>
    <ul class="mt-4">
        @foreach ($popularCommunities as $community)
        <li class="flex items-center justify-between py-2 border-b border-gray-200 last:border-b-0">
            <div class="flex items-center">
                <img src="{{ $community->icon_url ?? 'https://via.placeholder.com/32' }}" class="w-8 h-8 rounded-full object-cover">
                <div class="ml-3">
                    <a href="{{ route('communities.show', $community) }}" class="text-sm font-bold text-gray-800 hover:underline">
                        r/{{ $community->name }}
                    </a>
                    <p class="text-xs text-gray-500">{{ $community->members_count }} membros</p>
                </div>
            </div>
            <a href="{{ route('communities.show', $community) }}" class="text-xs font-semibold text-blue-500 border border-blue-500 rounded-full px-3 py-1 hover:bg-blue-500 hover:text-white">
                Ver
            </a>
        </li>
        @endforeach
    </ul>
</div>