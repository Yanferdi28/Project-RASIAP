<x-guest-layout>
    <div class="w-full max-w-sm mx-auto
                bg-white rounded-xl
                shadow-xl shadow-gray-900/10 ring-1 ring-gray-900/5
                dark:bg-gray-800 dark:shadow-gray-900/50 dark:ring-gray-700 relative">

        <!-- Dark mode toggle button -->
        <button data-toggle-dark-mode
                class="absolute top-4 right-4 p-2 rounded-full bg-white dark:bg-gray-800 shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                aria-label="Toggle dark mode">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path id="sun-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                <path id="moon-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" class="hidden"></path>
            </svg>
        </button>

        <div class="p-7 sm:p-8">

            <div class="text-center mb-4">
                {{-- Tailwind will show both and the media query in CSS will hide/show the correct one --}}
                <img src="{{ asset('images/logo-light.png') }}" alt="Logo Light" class="w-40 h-auto object-contain mx-auto block dark:hidden logo-light">
                <img src="{{ asset('images/logo-dark.png') }}" alt="Logo Dark" class="w-40 h-auto object-contain mx-auto hidden dark:block logo-dark">
            </div>

            <h1 class="text-xl font-semibold text-gray-900 text-center dark:text-gray-100">Portal Data Terpadu</h1>
            <p class="text-sm text-gray-500 text-center mt-1 dark:text-gray-400">Buat akun untuk memulai</p>


            <x-auth-session-status class="mb-4 mt-4" :status="session('status')" />

            <form method="POST" action="{{ route('register') }}" class="mt-6">
                @csrf

                <!-- Name -->
                <div class="mt-4">
                    <label for="name" class="sr-only">Nama</label>

                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M5.121 17.804A7 7 0 0112 14a7 7 0 016.879 3.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>

                        <x-text-input id="name"
                                        class="block w-full pl-10 text-sm py-3 rounded-xl border border-gray-200 bg-gray-100 text-gray-900 placeholder-gray-400
                                        focus:border-blue-500 focus:ring focus:ring-blue-500/30
                                        dark:bg-gray-700 dark:text-gray-50 dark:placeholder-gray-400 dark:border-gray-600"
                                        type="text"
                                        name="name"
                                        :value="old('name')"
                                        required
                                        autofocus
                                        autocomplete="name"
                                        placeholder="Nama Lengkap"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <!-- Email -->
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
                                        autocomplete="username"
                                        placeholder="Email"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Password -->
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
                                        autocomplete="new-password"
                                        placeholder="Password"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <label for="password_confirmation" class="sr-only">Konfirmasi Password</label>

                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zM8 9h8"/>
                        </svg>

                        <x-text-input id="password_confirmation"
                                        class="block w-full pl-10 text-sm py-3 rounded-xl border border-gray-200 bg-gray-100 text-gray-900 placeholder-gray-400
                                        focus:border-blue-500 focus:ring focus:ring-blue-500/30
                                        dark:bg-gray-700 dark:text-gray-50 dark:placeholder-gray-400 dark:border-gray-600"
                                        type="password"
                                        name="password_confirmation"
                                        required
                                        autocomplete="new-password"
                                        placeholder="Konfirmasi Password"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                <button type="submit" class="w-full justify-center mt-6 bg-blue-500 hover:bg-blue-600 focus:outline-none
                                        dark:bg-blue-600 dark:hover:bg-blue-700
                                        rounded-xl py-3 text-sm font-bold text-white">
                    Daftar
                </button>
            </form>

            <p class="text-center mt-5 text-sm text-gray-600 dark:text-gray-400">
                Sudah Punya Akun?
                <a href="{{ route('login') }}" class="font-semibold text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                    Masuk Disini
                </a>
            </p>
        </div>
    </div>

    <p class="text-center mt-5 text-xs text-gray-500 dark:text-gray-400">© {{ date('Y') }} All rights reserved.</p>

</x-guest-layout>