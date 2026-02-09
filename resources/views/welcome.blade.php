@extends('layouts.guest')

@section('content')
<div class="text-center">
    <h1 class="text-4xl font-bold text-white">Welcome to Lead Management</h1>
    <p class="text-gray-400 mt-2">Your one-stop solution for managing business leads.</p>
    <div class="mt-8">
        <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">Login</a>
        <a href="{{ route('register') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 ml-4">Register</a>
    </div>
</div>
@endsection
