
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            .icon-gray {
                filter: grayscale(100%);
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="flex h-screen bg-gray-100">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Navbar -->
                @include('layouts.navigation')

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                    <div class="container mx-auto px-6 py-8">
                        <!-- Page Heading -->
                        @if (isset($header))
                        <header class="bg-white shadow-md rounded-lg mb-8">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                <h1 class="text-3xl font-bold text-gray-800">
                                    {{ $header }}
                                </h1>
                            </div>
                        </header>
                        @endif
                        
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
