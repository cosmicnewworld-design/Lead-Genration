@extends('layouts.app')

@section('header', 'Add New Lead')

@section('content')
<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
    <form action="{{ route('leads.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300">Name</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label for="business_id" class="block text-sm font-medium text-gray-300">Business</label>
                <select name="business_id" id="business_id" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    @foreach($businesses as $business)
                        <option value="{{ $business->id }}">{{ $business->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="linkedin_profile" class="block text-sm font-medium text-gray-300">LinkedIn Profile</label>
                <input type="url" name="linkedin_profile" id="linkedin_profile" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <a href="{{ route('leads.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 mr-2">Cancel</a>
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">Create Lead</button>
        </div>
    </form>
</div>
@endsection
