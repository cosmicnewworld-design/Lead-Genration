<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-900 text-white">
        <div class="flex">
            <!-- Sidebar -->
            <aside class="w-64 bg-gray-800 shadow-md min-h-screen">
                <div class="p-6">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-white">🚀 Srijan Engine</a>
                </div>
                <nav class="mt-6">
                    <a href="{{ route('dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">Dashboard</a>
                    <a href="{{ route('leads.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">Leads</a>
                    <a href="{{ route('businesses.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">Businesses</a>
                    <!-- Add more links as we build features -->
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-10">
                <header class="mb-10">
                    <div class="flex justify-between items-center">
                        <h1 class="text-3xl font-bold">@yield('header')</h1>
                        <div>
                            @auth
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); this.closest('form').submit();"
                                       class="text-gray-400 hover:text-white">
                                        Log Out
                                    </a>
                                </form>
                            @endauth
                        </div>
                    </div>
                </header>
                
                @if (session('success'))
                    <div class="bg-green-500 text-white p-4 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
