@extends('layouts.app')

@section('header', 'Create Business')

@section('content')
<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
    <form action="{{ route('businesses.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300">Name</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label for="contact_email" class="block text-sm font-medium text-gray-300">Contact Email</label>
                <input type="email" name="contact_email" id="contact_email" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div>
                <label for="whatsapp_number" class="block text-sm font-medium text-gray-300">WhatsApp Number</label>
                <input type="text" name="whatsapp_number" id="whatsapp_number" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="target_audience" class="block text-sm font-medium text-gray-300">Target Audience</label>
                <input type="text" name="target_audience" id="target_audience" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                <textarea name="description" id="description" rows="4" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <a href="{{ route('businesses.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 mr-2">Cancel</a>
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">Create Business</button>
        </div>
    </form>
</div>
@endsection
