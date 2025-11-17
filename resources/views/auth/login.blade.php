<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal Data Terpadu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-700 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-sm mx-auto 
                bg-white rounded-xl 
                shadow-xl shadow-gray-900/10 ring-1 ring-gray-900/5
                dark:bg-gray-800 dark:shadow-gray-900/50 dark:ring-gray-700 relative">

        <!-- Dark mode toggle button -->
        <button id="toggleDarkMode" 
                class="absolute top-4 right-4 p-2 rounded-full bg-white dark:bg-gray-800 shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                aria-label="Toggle dark mode">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path id="sun-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                <path id="moon-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" class="hidden"></path>
            </svg>
        </button>

        <div class="p-7 sm:p-8">
            
            <div class="text-center mb-4">
                <!-- Logo dengan dark mode support -->
                <img src="images/logo-light.png" alt="Logo Light" class="w-40 h-auto object-contain mx-auto block dark:hidden">
                <img src="images/logo-dark.png" alt="Logo Dark" class="w-40 h-auto object-contain mx-auto hidden dark:block">
            </div>

            <h1 class="text-xl font-semibold text-gray-900 text-center dark:text-gray-100">Portal Data Terpadu</h1>
            <p class="text-sm text-gray-500 text-center mt-1 dark:text-gray-400">Selamat Datang</p>

            <form method="POST" action="{{ route('login') }}" class="mt-6">
                <!-- CSRF Token untuk Laravel -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <!-- Display general error messages -->
                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm dark:bg-red-900/30 dark:border-red-700 dark:text-red-300">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mt-4">
                    <label for="email" class="sr-only">Email or Name</label>

                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5A2 2 0 003 7v10a2 2 0 002 2z"/>
                        </svg>

                        <input id="email"
                                class="block w-full pl-10 text-sm py-3 rounded-xl border border-gray-200 bg-gray-100 text-gray-900 placeholder-gray-400
                                focus:border-blue-500 focus:ring focus:ring-blue-500/30
                                dark:bg-gray-700 dark:text-gray-50 dark:placeholder-gray-400 dark:border-gray-600
                                @if($errors->has('email')) border-red-500 @endif"
                                type="text"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Email atau Nama Pengguna"
                        />
                    </div>

                    @if($errors->has('email'))
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <div class="mt-4">
                    <label for="password" class="sr-only">Password</label>

                    <div class="relative">
                        <!-- icon gembok di kiri -->
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v4"/>
                        </svg>

                        <!-- input password -->
                        <input id="password"
                            class="block w-full pl-10 pr-11 text-sm py-3 rounded-xl border border-gray-200 bg-gray-100 text-gray-900 placeholder-gray-400
                            focus:border-blue-500 focus:ring focus:ring-blue-500/30
                            dark:bg-gray-700 dark:text-gray-50 dark:placeholder-gray-400 dark:border-gray-600
                            @error('password') border-red-500 @enderror"
                            type="password"
                            name="password"
                            value="{{ old('password') }}"
                            required
                            autocomplete="current-password"
                            placeholder="Password"
                        />

                        <!-- tombol icon mata di KANAN -->
                        <button
                            type="button"
                            id="togglePassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none z-10"
                            aria-label="Toggle password visibility"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <!-- Eye open -->
                                <g class="eye-open">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                        d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"/>
                                </g>
                                <!-- Eye closed -->
                                <g class="eye-closed hidden">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                        d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/>
                                    <line x1="1" y1="1" x2="23" y2="23" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"/>
                                </g>
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between items-center mt-3">
                    <label for="remember_me" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-gray-900 shadow-sm focus:ring-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-500" {{ old('remember') ? 'checked' : '' }}>
                        <span class="ms-2">Ingat Password</span>
                    </label>

                    <a class="text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400" href="{{ route('password.request') }}">
                        Lupa Password?
                    </a>
                </div>

                <button type="submit" class="w-full justify-center mt-4 bg-blue-500 hover:bg-blue-600 focus:outline-none
                                        dark:bg-blue-600 dark:hover:bg-blue-700
                                        rounded-xl py-3 text-sm font-bold text-white">
                    Login
                </button>
            </form>
            
            <p class="text-center mt-5 text-sm text-gray-600 dark:text-gray-400">
                Tidak Punya Akun?
                <a href="register" class="font-semibold text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                    Buat Akun
                </a>
            </p>
            <p class="text-center text-xs text-gray-500 dark:text-gray-400">© 2024 All rights reserved.</p>
        </div>
    </div>

    <script>
        // Dark mode toggle
        const toggleDarkMode = document.getElementById('toggleDarkMode');
        const sunIcon = document.getElementById('sun-icon');
        const moonIcon = document.getElementById('moon-icon');
        
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            sunIcon.classList.add('hidden');
            moonIcon.classList.remove('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            sunIcon.classList.remove('hidden');
            moonIcon.classList.add('hidden');
        }
        
        toggleDarkMode.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            
            if (document.documentElement.classList.contains('dark')) {
                localStorage.theme = 'dark';
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            } else {
                localStorage.theme = 'light';
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            }
        });

        // Password visibility toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.querySelector('.eye-open');
        const eyeClosed = document.querySelector('.eye-closed');

        if(togglePassword && passwordInput && eyeOpen && eyeClosed) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle eye icons
                if(type === 'text') {
                    eyeOpen.classList.add('hidden');
                    eyeClosed.classList.remove('hidden');
                } else {
                    eyeOpen.classList.remove('hidden');
                    eyeClosed.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>