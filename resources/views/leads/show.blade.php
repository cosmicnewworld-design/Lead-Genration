@extends('layouts.app')

@section('header', 'Lead Details')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-gray-800 rounded-lg shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-4xl font-extrabold text-white">{{ $lead->name }}</h2>
                    <span class="px-4 py-2 text-sm font-bold rounded-full
                        @switch($lead->status)
                            @case('New') bg-blue-500 text-white @break
                            @case('Contacted') bg-yellow-500 text-gray-900 @break
                            @case('Replied') bg-green-500 text-white @break
                            @case('Junk') bg-red-500 text-white @break
                        @endswitch">
                        {{ $lead->status }}
                    </span>
                </div>
                <p class="text-lg text-gray-400 mt-2">{{ $lead->designation }} at <a href="#" class="text-indigo-400 hover:underline">{{ $lead->company_name }}</a></p>

                <div class="mt-8 border-t border-gray-700 pt-6">
                    <h3 class="text-2xl font-bold text-white mb-4">Contact Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-lg">
                        <div class="flex items-center">
                            <i class="fas fa-envelope fa-lg text-gray-400 mr-4"></i>
                            <a href="mailto:{{ $lead->email }}" class="text-indigo-400 hover:underline">{{ $lead->email }}</a>
                        </div>
                        @if($lead->phone)
                        <div class="flex items-center">
                            <i class="fas fa-phone fa-lg text-gray-400 mr-4"></i>
                            <span class="text-gray-300">{{ $lead->phone }}</span>
                        </div>
                        @endif
                        @if($lead->linkedin_profile)
                        <div class="flex items-center">
                            <i class="fab fa-linkedin fa-lg text-gray-400 mr-4"></i>
                            <a href="{{ $lead->linkedin_profile }}" target="_blank" class="text-indigo-400 hover:underline">LinkedIn Profile</a>
                        </div>
                        @endif
                    </div>
                </div>

                @if($lead->business)
                <div class="mt-8 border-t border-gray-700 pt-6">
                    <h3 class="text-2xl font-bold text-white mb-4">Associated Business</h3>
                    <p class="text-lg text-gray-300">{{ $lead->business->name }}</p>
                </div>
                @endif
            </div>

            <div class="bg-gray-900 px-8 py-4 flex justify-end">
                <a href="{{ route('leads.index') }}" class="text-gray-400 hover:text-white font-medium py-2 px-4 rounded-lg transition duration-200 mr-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Leads
                </a>
                <a href="{{ route('leads.edit', $lead->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-edit mr-2"></i> Edit Lead
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
