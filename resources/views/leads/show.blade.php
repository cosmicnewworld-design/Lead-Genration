@extends('layouts.app')

@section('header', 'Lead Details')

@section('content')
<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-medium text-white">{{ $lead->name }}</h3>
            <p class="mt-1 text-sm text-gray-400">{{ $lead->email }}</p>
        </div>
        <div>
            <h3 class="text-lg font-medium text-white">Business</h3>
            <p class="mt-1 text-sm text-gray-400">{{ $lead->business->name }}</p>
        </div>
        <div>
            <h3 class="text-lg font-medium text-white">Status</h3>
            <p class="mt-1 text-sm text-gray-400">{{ ucfirst($lead->status) }}</p>
        </div>
        <div>
            <h3 class="text-lg font-medium text-white">LinkedIn Profile</h3>
            <p class="mt-1 text-sm text-gray-400">
                @if ($lead->linkedin_profile)
                    <a href="{{ $lead->linkedin_profile }}" target="_blank" class="text-indigo-400 hover:text-indigo-600">View Profile</a>
                @else
                    N/A
                @endif
            </p>
        </div>
        <div>
            <h3 class="text-lg font-medium text-white">Email Verified</h3>
            <p class="mt-1 text-sm text-gray-400">
                @if ($lead->email_verified_at)
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900 text-green-300">Verified</span>
                @else
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-900 text-red-300">Not Verified</span>
                @endif
            </p>
        </div>
    </div>
    <div class="mt-6 flex justify-end">
        <a href="{{ route('leads.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">Back to Leads</a>
    </div>
</div>
@endsection
