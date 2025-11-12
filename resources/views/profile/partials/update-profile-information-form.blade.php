<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informações do Perfil') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Atualize as informações do seu perfil e o endereço de e-mail.") }}
        </p>
    </header>

    {{-- ADICIONADO enctype PARA PERMITIR UPLOAD DE FICHEIROS --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- CAMPO DE FOTO DE PERFIL --}}
        <div class="flex items-center gap-4">
            {{-- AQUI ESTÁ A LÓGICA PARA MOSTRAR A IMAGEM --}}
            <img class="h-20 w-20 rounded-full object-cover" 
                src="{{ Auth::user()->image_profile ? asset('storage/' . Auth::user()->image_profile) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF' }}" 
                alt="{{ Auth::user()->name }}">
            
            <div class="flex-grow">
                <x-input-label for="image_profile" :value="__('Nova Foto de Perfil')" />
                <input id="image_profile" name="image_profile" type="file" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                <p class="mt-1 text-xs text-gray-500">PNG, JPG ou JPEG (MAX. 2MB).</p>
                <x-input-error class="mt-2" :messages="$errors->get('image_profile')" />
            </div>
        </div>
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Salvar') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Salvo.') }}</p>
            @endif
        </div>
    </form>
</section>