@props(['post'])

<div class="card flex mb-4 transition hover:shadow-lg">
    {{-- Votos / Reações --}}
    <div class="flex flex-col items-center p-3 bg-beige-100 rounded-l-lg border-r border-beige-200">
        <form action="{{ route('posts.toggleLike', $post) }}" method="POST">
            @csrf
            <button type="submit" class="text-primary-600 hover:text-red-500 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 @if(auth()->check() && $post->likedByUsers->contains(auth()->user())) text-red-500 @endif">
                    <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 015.656 5.656L10 18.657l-6.828-6.829a4 4 0 010-5.656z" />
                </svg>
            </button>
        </form>
        <span class="text-xs font-semibold mt-1">{{ $post->likedByUsers->count() }}</span>
    </div>

    {{-- Conteúdo do Post --}}
    <div class="p-4 flex-grow">
    <p class="text-xs text-black mb-1">
            @if ($post->community)
                <a href="{{ route('communities.show', $post->community) }}" class="font-semibold text-secondary-600 hover:underline">r/{{ $post->community->name }}</a>
                <span class="mx-2">•</span>
            @endif
            Postado por
            <a href="{{ route('profile.show', $post->user) }}" class="font-semibold text-gray-800 hover:underline">{{ $post->user->name }}</a>
            <span class="mx-2">•</span> {{ $post->created_at->diffForHumans() }}
        </p>

        <a href="{{ route('posts.show', $post) }}">
            <h3 class="text-lg font-semibold mt-1 text-black hover:text-secondary-600 transition">{{ $post->title }}</h3>
        </a>

        @if ($post->image_url)
            <img src="{{ asset('storage/' . $post->image_url) }}" alt="Imagem do Post" class="mt-3 rounded-lg max-h-96 w-full object-cover">
        @else
            <p class="mt-2 text-sm text-black">{{ Str::limit($post->content, 220) }}</p>
        @endif

    <div class="mt-3 flex items-center space-x-4 text-sm text-black">
            <a href="{{ route('posts.show', $post) }}" class="flex items-center space-x-2 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10 2c-4.418 0-8 3.134-8 7 0 2.04.784 3.915 2.11 5.333L2 18l3.333-1.11a7.96 7.96 0 005.334 2.11c4.418 0 8-3.134 8-7s-3.582-7-8-7z" clip-rule="evenodd" /></svg>
                <span>{{ $post->comments->count() }} comentários</span>
            </a>

            <button class="flex items-center space-x-2 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path d="M13 4.5a2.5 2.5 0 11.702 4.283l-5.236 2.441a2.5 2.5 0 11-.232-.823l5.132-2.394A2.5 2.5 0 0113 4.5zM4.5 10a2.5 2.5 0 100 5 2.5 2.5 0 000-5zM13 15.5a2.5 2.5 0 100 5 2.5 2.5 0 000-5z" /></svg>
                <span>Compartilhar</span>
            </button>
        </div>
    </div>
</div>