@extends('layouts.app')

@section('header', 'Manage Leads')

@section('content')
<div class="bg-gray-800 p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">All Leads</h2>
        <a href="{{ route('leads.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">+ Add New Lead</a>
    </div>

    <!-- Filters -->
    <div class="mb-4">
        <form action="{{ route('leads.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <select name="status" id="status" class="bg-gray-700 text-white border-2 border-gray-600 rounded-lg py-2 px-3 focus:outline-none focus:border-indigo-500" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Leads Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-gray-800">
            <thead class="bg-gray-700">
                <tr>
                    <th class="py-3 px-6 text-left text-sm font-semibold text-gray-300 uppercase tracking-wider">Name</th>
                    <th class="py-3 px-6 text-left text-sm font-semibold text-gray-300 uppercase tracking-wider">Business</th>
                    <th class="py-3 px-6 text-center text-sm font-semibold text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="py-3 px-6 text-center text-sm font-semibold text-gray-300 uppercase tracking-wider">Email Verified</th>
                    <th class="py-3 px-6 text-center text-sm font-semibold text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-400">
                @forelse ($leads as $lead)
                    <tr class="border-b border-gray-700 hover:bg-gray-700">
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">{{ $lead->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $lead->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap text-sm">{{ $lead->business->name }}</td>
                        <td class="py-4 px-6 whitespace-nowrap text-center">
                             <form action="{{ route('leads.updateStatus', $lead->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="bg-gray-700 text-white border-2 border-gray-600 rounded-lg py-1 px-2 focus:outline-none focus:border-indigo-500 text-xs" onchange="this.form.submit()">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" {{ $lead->status == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap text-center text-sm">
                            @if ($lead->email_verified_at)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900 text-green-300">Verified</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-900 text-red-300">Not Verified</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('leads.show', $lead->id) }}" class="text-indigo-400 hover:text-indigo-600 mr-3">View</a>
                            <a href="{{ route('leads.edit', $lead->id) }}" class="text-yellow-400 hover:text-yellow-600 mr-3">Edit</a>
                            <form action="{{ route('leads.destroy', $lead->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-500">
                            <h3 class="text-xl">No Leads Found</h3>
                            <p class="mt-2">Looks like you haven't added any leads yet. Get started by adding a new one!</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $leads->links() }}
    </div>
</div>
@endsection
