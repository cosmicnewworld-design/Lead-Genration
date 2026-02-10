@extends('layouts.app')

@section('header', 'Add New Lead')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-4xl mx-auto bg-gray-800 rounded-lg shadow-2xl">
        <div class="p-8">
            <h2 class="text-3xl font-bold text-white mb-6">Create a New Lead</h2>

            <form action="{{ route('leads.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full bg-gray-700 border-gray-600 rounded-lg shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4" placeholder="e.g., John Doe" required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full bg-gray-700 border-gray-600 rounded-lg shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4" placeholder="e.g., john.doe@example.com" required>
                        @error('email')
                            <p class="mt-2 text-sm text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="w-full bg-gray-700 border-gray-600 rounded-lg shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4" placeholder="e.g., +1 123 456 7890">
                         @error('phone')
                            <p class="mt-2 text-sm text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="designation" class="block text-sm font-medium text-gray-300 mb-2">Designation</label>
                        <input type="text" name="designation" id="designation" value="{{ old('designation') }}" class="w-full bg-gray-700 border-gray-600 rounded-lg shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4" placeholder="e.g., Software Engineer">
                        @error('designation')
                            <p class="mt-2 text-sm text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="company_name" class="block text-sm font-medium text-gray-300 mb-2">Company Name</label>
                        <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" class="w-full bg-gray-700 border-gray-600 rounded-lg shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4" placeholder="e.g., Acme Corporation">
                        @error('company_name')
                            <p class="mt-2 text-sm text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="business_id" class="block text-sm font-medium text-gray-300 mb-2">Business</label>
                        <select name="business_id" id="business_id" class="w-full bg-gray-700 border-gray-600 rounded-lg shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4" required>
                            <option value="" disabled selected>Select a business</option>
                            @foreach($businesses as $business)
                                <option value="{{ $business->id }}" {{ old('business_id') == $business->id ? 'selected' : '' }}>{{ $business->name }}</option>
                            @endforeach
                        </select>
                        @error('business_id')
                            <p class="mt-2 text-sm text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="linkedin_profile" class="block text-sm font-medium text-gray-300 mb-2">LinkedIn Profile</label>
                        <input type="url" name="linkedin_profile" id="linkedin_profile" value="{{ old('linkedin_profile') }}" class="w-full bg-gray-700 border-gray-600 rounded-lg shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4" placeholder="e.g., https://linkedin.com/in/johndoe">
                        @error('linkedin_profile')
                            <p class="mt-2 text-sm text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-700 flex justify-end items-center">
                    <a href="{{ route('leads.index') }}" class="text-gray-400 hover:text-white font-medium py-2 px-4 rounded-lg transition duration-200 mr-4">Cancel</a>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                        <i class="fas fa-plus-circle mr-2"></i> Create Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
