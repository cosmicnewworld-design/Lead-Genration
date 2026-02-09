@extends('layouts.app')

@section('header', 'Outreach to ' . $business->name)

@section('content')
<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
    <form action="{{ route('businesses.outreach.send', $business->id) }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="subject" class="block text-sm font-medium text-gray-300">Subject</label>
            <input type="text" name="subject" id="subject" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $subject }}" required>
        </div>
        <div class="mb-4">
            <label for="body" class="block text-sm font-medium text-gray-300">Body</label>
            <textarea name="body" id="body" rows="10" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $body }}</textarea>
        </div>
        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">Send Outreach</button>
        </div>
    </form>
</div>
@endsection
