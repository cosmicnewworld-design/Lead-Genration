@extends('layouts.app')

@section('header', 'Edit Lead')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-4xl mx-auto bg-gray-800 rounded-lg shadow-2xl">
        <div class="p-8">
            <h2 class="text-3xl font-bold text-white mb-6">Edit Lead</h2>

            <form action="{{ route('leads.update', $lead->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $lead->name) }}" class="w-full bg-gray-700 border-gray-600 rounded-lg shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4" required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $lead->email) }}" class="w-full bg-gray-700 border-gray-600 rounded-lg shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4" required>
                        @error('email')
                            <p class="mt-2 text-sm text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $lead->phone) }}" class="w-full bg-gray-700 border-gray-600 rounded-lg shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="designation" class="block text-sm font-medium text-gray-300 mb-2">Designation</label>
                        <input type="text" name="designation" id="designation" value="{{ old('designation', $lead->designation) }}" class="w-full bg-gray-700 border-gray-600 rounded-lg shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4">
                        @error('designation')
                            <p class="mt-2 text-sm text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="company_name" class="block text-sm font-medium text-gray-300 mb-2">Company Name</label>
                        <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $lead->company_name) }}" class="w-full bg-gray-700 border-gray-600 rounded-lg shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4">
                        @error('company_name')
                            <p class="mt-2 text-sm text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="business_id" class="block text-sm font-medium text-gray-300 mb-2">Business</label>
                        <select name="business_id" id="business_id" class="w-full bg-gray-700 border-gray-600 rounded-lg shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4" required>
                            @foreach($businesses as $business)
                                <option value="{{ $business->id }}" {{ old('business_id', $lead->business_id) == $business->id ? 'selected' : '' }}>{{ $business->name }}</option>
                            @endforeach
                        </select>
                        @error('business_id')
                            <p class="mt-2 text-sm text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="linkedin_profile" class="block text-sm font-medium text-gray-300 mb-2">LinkedIn Profile</label>
                        <input type="url" name="linkedin_profile" id="linkedin_profile" value="{{ old('linkedin_profile', $lead->linkedin_profile) }}" class="w-full bg-gray-700 border-gray-600 rounded-lg shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4">
                        @error('linkedin_profile')
                            <p class="mt-2 text-sm text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}</p>
                        @enderror
                    </div>
                     <div>
                        <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                        <select name="status" id="status" class="w-full bg-gray-700 border-gray-600 rounded-lg shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4">
                            <option value="New" {{ old('status', $lead->status) == 'New' ? 'selected' : '' }}>New</option>
                            <option value="Contacted" {{ old('status', $lead->status) == 'Contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="Replied" {{ old('status', $lead->status) == 'Replied' ? 'selected' : '' }}>Replied</option>
                            <option value="Junk" {{ old('status', $lead->status) == 'Junk' ? 'selected' : '' }}>Junk</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-400 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-700 flex justify-end items-center">
                    <a href="{{ route('leads.index') }}" class="text-gray-400 hover:text-white font-medium py-2 px-4 rounded-lg transition duration-200 mr-4">Cancel</a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i> Update Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
