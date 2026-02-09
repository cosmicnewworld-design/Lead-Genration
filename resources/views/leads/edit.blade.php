@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">Edit Lead</h1>
            <a href="{{ route('leads.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back</a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('leads.update', $lead->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                    <input type="text" name="name" id="name" value="{{ $lead->name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="designation" class="block text-gray-700 text-sm font-bold mb-2">Designation:</label>
                    <input type="text" name="designation" id="designation" value="{{ $lead->designation }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label for="company_name" class="block text-gray-700 text-sm font-bold mb-2">Company Name:</label>
                    <input type="text" name="company_name" id="company_name" value="{{ $lead->company_name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                    <input type="email" name="email" id="email" value="{{ $lead->email }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                    <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="New" {{ $lead->status == 'New' ? 'selected' : '' }}>New</option>
                        <option value="Contacted" {{ $lead->status == 'Contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="Replied" {{ $lead->status == 'Replied' ? 'selected' : '' }}>Replied</option>
                        <option value="Junk" {{ $lead->status == 'Junk' ? 'selected' : '' }}>Junk</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="business_id" class="block text-gray-700 text-sm font-bold mb-2">Business:</label>
                    <select name="business_id" id="business_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        @foreach ($businesses as $business)
                            <option value="{{ $business->id }}" {{ $lead->business_id == $business->id ? 'selected' : '' }}>{{ $business->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">LinkedIn Profile:</label>
                    @if ($lead->linkedin_profile)
                        <a href="{{ $lead->linkedin_profile }}" target="_blank" class="text-blue-500 hover:text-blue-700">{{ $lead->linkedin_profile }}</a>
                    @else
                        <p class="text-gray-700">N/A</p>
                    @endif
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email Verified:</label>
                    @if ($lead->email_verified_at)
                        <p class="text-green-500">Verified on {{ $lead->email_verified_at->format('Y-m-d') }}</p>
                    @else
                        <p class="text-red-500">Not Verified</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Submit
                </button>
            </div>
        </form>
    </div>
@endsection
