<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-beige-50 text-black">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow-sm">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

                    <!-- Barra Lateral Esquerda -->
                    <aside class="col-span-12 md:col-span-2">
                                <div class="sticky top-20">
                                    <nav class="space-y-2">
                                        <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-secondary-700' : 'text-black hover:bg-primary-50 hover:shadow-sm' }}">
                                    Página Inicial
                                </a>
                                        <a href="{{ route('communities.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('communities.*') ? 'bg-primary-50 text-secondary-700' : 'text-black hover:bg-primary-50 hover:shadow-sm' }}">
                                    Comunidades
                                </a>
                                        <a href="#" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-black hover:bg-primary-50 hover:shadow-sm">
                                    Popular
                                </a>
                            </nav>
                        </div>
                    </aside>

                    <!-- Conteúdo Principal -->
                    <div class="col-span-12 md:col-span-7">
                        {{ $slot }}
                    </div>

                    <!-- Barra Lateral Direita -->
                    <aside class="col-span-12 md:col-span-3">
                        <div class="sticky top-20 space-y-4">
                            @isset($rightSidebar)
                                {{ $rightSidebar }}
                            @else
                                <div class="bg-beige-100 p-4 rounded-lg shadow-sm border border-beige-200">
                                    <h3 class="font-bold text-lg text-primary-800">Sobre a Rede</h3>
                                    <p class="text-sm mt-2 text-primary-700">
                                        Bem-vindo à nossa comunidade!
                                    </p>
                                </div>
                            @endisset
                        </div>
                    </aside>

                </div>
            </main>
        </div>
        <footer class="bg-secondary-100 text-black border-t border-secondary-200 mt-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex items-center justify-between">
                <div class="text-sm">&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</div>
                <div class="text-sm">
                    <a href="#" class="hover:underline text-black">Sobre</a>
                    <span class="mx-2">•</span>
                    <a href="#" class="hover:underline text-black">Contato</a>
                </div>
            </div>
        </footer>
    </body>
</html>