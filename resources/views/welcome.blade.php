
<x-app-layout>
    <div class="relative flex items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 text-center">
            <div class="p-10 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none">
                <h1 class="text-5xl font-extrabold text-gray-800 dark:text-white mb-4">
                    Welcome to Your Business Hub
                </h1>

                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                    Manage your businesses, leads, and outreach campaigns all in one place.
                </p>

                <a href="{{ route('businesses.index') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg text-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    Get Started
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
