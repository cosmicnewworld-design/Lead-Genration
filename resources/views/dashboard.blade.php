@extends('layouts.app')

@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Total Businesses -->
    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-600 bg-opacity-75">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-lg font-semibold text-gray-400">Total Businesses</p>
                <p class="text-2xl font-bold text-white">{{ $totalBusinesses }}</p>
            </div>
        </div>
    </div>

    <!-- Total Leads -->
    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-600 bg-opacity-75">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-lg font-semibold text-gray-400">Total Leads</p>
                <p class="text-2xl font-bold text-white">{{ $totalLeads }}</p>
            </div>
        </div>
    </div>

    <!-- Verified Leads -->
    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-600 bg-opacity-75">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-lg font-semibold text-gray-400">Verified Leads</p>
                <p class="text-2xl font-bold text-white">{{ $verifiedLeads }}</p>
            </div>
        </div>
    </div>

    <!-- Outreach Sent -->
    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-600 bg-opacity-75">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-lg font-semibold text-gray-400">Outreach Sent</p>
                <p class="text-2xl font-bold text-white">{{ $outreachSent }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Leads -->
<div class="mt-8 bg-gray-800 p-6 rounded-lg shadow-lg">
    <h3 class="text-xl font-bold text-white mb-4">Recent Leads</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-gray-800">
            <thead class="bg-gray-700">
                <tr>
                    <th class="py-3 px-6 text-left text-sm font-semibold text-gray-300 uppercase tracking-wider">Name</th>
                    <th class="py-3 px-6 text-left text-sm font-semibold text-gray-300 uppercase tracking-wider">Business</th>
                    <th class="py-3 px-6 text-left text-sm font-semibold text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="py-3 px-6 text-center text-sm font-semibold text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-400">
                @forelse ($recentLeads as $lead)
                    <tr class="border-b border-gray-700 hover:bg-gray-700">
                        <td class="py-4 px-6 whitespace-nowrap">{{ $lead->name }}</td>
                        <td class="py-4 px-6 whitespace-nowrap">{{ $lead->business->name }}</td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $lead->status === 'verified' ? 'bg-green-900 text-green-300' : 'bg-yellow-900 text-yellow-300' }}">
                                {{ ucfirst($lead->status) }}
                            </span>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('leads.show', $lead->id) }}" class="text-indigo-400 hover:text-indigo-600">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-10 text-gray-500">No recent leads found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
