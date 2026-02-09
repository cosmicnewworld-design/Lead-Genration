@extends('layouts.app')

@section('header', 'Manage Businesses')

@section('content')
<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">All Businesses</h2>
        <a href="{{ route('businesses.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">+ Add New Business</a>
    </div>

    <!-- Businesses Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-gray-800">
            <thead class="bg-gray-700">
                <tr>
                    <th class="py-3 px-6 text-left text-sm font-semibold text-gray-300 uppercase tracking-wider">Name</th>
                    <th class="py-3 px-6 text-left text-sm font-semibold text-gray-300 uppercase tracking-wider">Contact</th>
                    <th class="py-3 px-6 text-left text-sm font-semibold text-gray-300 uppercase tracking-wider">Target Audience</th>
                    <th class="py-3 px-6 text-center text-sm font-semibold text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-400">
                @forelse ($businesses as $business)
                    <tr class="border-b border-gray-700 hover:bg-gray-700">
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">{{ $business->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($business->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap text-sm">
                            <div class="text-white">{{ $business->contact_email }}</div>
                            <div class="text-gray-500">{{ $business->whatsapp_number }}</div>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap text-sm">{{ $business->target_audience }}</td>
                        <td class="py-4 px-6 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('businesses.outreach', $business->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200 mr-2">Outreach</a>
                            <a href="{{ route('businesses.show', $business->id) }}" class="text-indigo-400 hover:text-indigo-600 mr-3">View</a>
                            <a href="{{ route('businesses.edit', $business->id) }}" class="text-yellow-400 hover:text-yellow-600 mr-3">Edit</a>
                            <form action="{{ route('businesses.destroy', $business->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-10 text-gray-500">
                            <h3 class="text-xl">No Businesses Found</h3>
                            <p class="mt-2">Get started by creating a new business profile.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $businesses->links() }}
    </div>
</div>
@endsection
