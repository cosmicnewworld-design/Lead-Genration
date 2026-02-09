<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lead Management</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-gray-900 text-white">
        <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-red-500 selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:justify-center lg:col-start-2">
                       <h1 class="text-4xl font-bold">Lead Management</h1>
                    </div>
                    @if (Route::has('login'))
                        <nav class="flex flex-1 justify-end">
                            @auth
                                <a
                                    href="{{ url('/dashboard') }}"
                                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                                >
                                    Dashboard
                                </a>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                                >
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a
                                        href="{{ route('register') }}"
                                        class="ml-4 rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                                    >
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </header>

                <main class="mt-6">
                    <div class='text-center'>
                        <h2 class='text-5xl font-bold'>Welcome to the Future of Lead Management</h2>
                        <p class='mt-4 text-lg text-gray-400'>A streamlined solution to help you find, track, and convert leads into customers.</p>
                         <div class="mt-8">
                            <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 text-lg">Get Started</a>
                        </div>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-3 lg:gap-8 mt-16">
                        <div class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-gray-800 p-6 shadow-lg">
                            <h3 class="text-2xl font-bold">Powerful Scraping</h3>
                            <p class="text-gray-400">Effortlessly gather lead information from various online sources.</p>
                        </div>

                        <div class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-gray-800 p-6 shadow-lg">
                            <h3 class="text-2xl font-bold">Automated Outreach</h3>
                            <p class="text-gray-400">Engage with your leads through automated and personalized email campaigns.</p>
                        </div>

                        <div class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-gray-800 p-6 shadow-lg">
                           <h3 class="text-2xl font-bold">Advanced Analytics</h3>
                            <p class="text-gray-400">Track your progress and make data-driven decisions with our intuitive dashboard.</p>
                        </div>
                    </div>
                </main>

                <footer class="py-16 text-center text-sm text-gray-400">
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </footer>
            </div>
        </div>
    </body>
</html>
