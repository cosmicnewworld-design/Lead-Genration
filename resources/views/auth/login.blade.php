@extends('layouts.guest')

@section('content')
<div class="max-w-md w-full bg-gray-800 p-8 rounded-lg shadow-lg">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-white">Welcome Back</h2>
        <p class="text-gray-400">Sign in to continue to your dashboard</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
            <input id="email" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-6">
             <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
            <input id="password" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-600 bg-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <label for="remember_me" class="ml-2 block text-sm text-gray-400">Remember me</label>
            </div>

            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-400 hover:text-indigo-600" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Log in
            </button>
        </div>

        <!-- Sign Up Link -->
        <p class="text-center text-sm text-gray-400 mt-6">
            Don't have an account? 
            <a href="{{ route('register') }}" class="font-medium text-indigo-400 hover:text-indigo-600">
                Sign up
            </a>
        </p>
    </form>
</div>
@endsection
