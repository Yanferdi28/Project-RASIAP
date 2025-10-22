<x-guest-layout>
    <div class="w-full max-w-sm mx-auto 
                bg-white rounded-xl 
                shadow-xl shadow-gray-900/10 ring-1 ring-gray-900/5
                dark:bg-gray-800 dark:shadow-gray-900/50 dark:ring-gray-700">

        <div class="p-7 sm:p-8">
            
            <div class="text-center mb-4">
                {{-- Tailwind akan menampilkan keduanya dan media query di CSS akan menyembunyikan/menampilkan yang benar --}}
                <img src="{{ asset('images/logo-light.png') }}" alt="Logo Light" class="w-24 h-auto object-contain mx-auto block dark:hidden">
                <img src="{{ asset('images/logo-dark.png') }}" alt="Logo Dark" class="w-24 h-auto object-contain mx-auto hidden dark:block">
            </div>

            <h1 class="text-xl font-semibold text-gray-900 text-center dark:text-gray-100">Login dengan Email</h1>
            <p class="text-sm text-gray-500 text-center mt-1 dark:text-gray-400">Selamat Datang</p>
            
            <x-auth-session-status class="mb-4 mt-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="mt-6">
                @csrf

                <div class="mt-4">
                    <label for="email" class="sr-only">Email</label>

                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5A2 2 0 003 7v10a2 2 0 002 2z"/>
                        </svg>
                        
                        <x-text-input id="email" 
                                        class="block w-full pl-10 text-sm py-3 rounded-xl border border-gray-200 bg-gray-100 text-gray-900 placeholder-gray-400 
                                        focus:border-blue-500 focus:ring focus:ring-blue-500/30
                                        dark:bg-gray-700 dark:text-gray-50 dark:placeholder-gray-400 dark:border-gray-600"
                                        type="email"
                                        name="email"
                                        :value="old('email')"
                                        required
                                        autofocus
                                        autocomplete="username"
                                        placeholder="Email"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div class="mt-4">
                    <label for="password" class="sr-only">Password</label>

                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v4"/>
                        </svg>

                        <x-text-input id="password" 
                                        class="block w-full pl-10 text-sm py-3 rounded-xl border border-gray-200 bg-gray-100 text-gray-900 placeholder-gray-400 
                                        focus:border-blue-500 focus:ring focus:ring-blue-500/30
                                        dark:bg-gray-700 dark:text-gray-50 dark:placeholder-gray-400 dark:border-gray-600"
                                        type="password"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder="Password"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div class="flex justify-between items-center mt-3"> 
                    <label for="remember_me" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-gray-900 shadow-sm focus:ring-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-500">
                        <span class="ms-2">Ingat Password</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400" href="{{ route('password.request') }}">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <x-primary-button class="w-full justify-center mt-4 bg-gray-900 hover:bg-black focus:ring-gray-900/50 
                                        dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-600/50 
                                        rounded-xl py-3 text-sm font-bold text-white">
                    Login
                </x-primary-button>
            </form>
            
            @if (Route::has('register'))
                <p class="text-center mt-5 text-sm text-gray-600 dark:text-gray-400">
                    Tidak Punya Akun?
                    <a href="{{ route('register') }}" class="font-semibold text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        Buat Akun
                    </a>
                </p>
            @endif
        </div>
    </div>

    <p class="text-center mt-5 text-xs text-gray-500 dark:text-gray-400">© {{ date('Y') }} All rights reserved.</p>

</x-guest-layout>