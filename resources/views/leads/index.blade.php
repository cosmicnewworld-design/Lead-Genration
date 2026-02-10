@extends('layouts.app')

@section('header', 'Leads')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-4xl font-extrabold text-white">Leads</h1>
        <a href="{{ route('leads.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
            <i class="fas fa-plus mr-2"></i> Add New Lead
        </a>
    </div>

    @if (session('success'))
        <div id="success-alert" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md mb-6" role="alert">
            <div class="flex">
                <div class="py-1"><i class="fas fa-check-circle fa-2x mr-4"></i></div>
                <div>
                    <p class="font-bold">Success!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8" onclick="document.getElementById('success-alert').style.display='none'">
                    <span class="sr-only">Close</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <div class="bg-gray-800 rounded-lg shadow-2xl p-6">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="py-4 px-6 text-left text-sm font-semibold text-gray-400 uppercase tracking-wider">Name</th>
                    <th class="py-4 px-6 text-left text-sm font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="py-4 px-6 text-left text-sm font-semibold text-gray-400 uppercase tracking-wider">Business</th>
                    <th class="py-4 px-6 text-left text-sm font-semibold text-gray-400 uppercase tracking-wider">Email</th>
                    <th class="py-4 px-6 text-center text-sm font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="text-white">
                @forelse ($leads as $lead)
                    <tr class="border-b border-gray-700 hover:bg-gray-700 transition duration-200">
                        <td class="py-4 px-6">{{ $lead->name }}</td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 text-xs font-bold rounded-full
                                @switch($lead->status)
                                    @case('New') bg-blue-500 text-white @break
                                    @case('Contacted') bg-yellow-500 text-gray-900 @break
                                    @case('Replied') bg-green-500 text-white @break
                                    @case('Junk') bg-red-500 text-white @break
                                @endswitch">
                                {{ $lead->status }}
                            </span>
                        </td>
                        <td class="py-4 px-6">{{ $lead->business->name ?? 'N/A' }}</td>
                        <td class="py-4 px-6">{{ $lead->email }}</td>
                        <td class="py-4 px-6 text-center">
                            <a href="{{ route('leads.show', $lead->id) }}" class="text-blue-400 hover:text-blue-300 mr-4 transition duration-200"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('leads.edit', $lead->id) }}" class="text-yellow-400 hover:text-yellow-300 mr-4 transition duration-200"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('leads.destroy', $lead->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this lead?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-400 transition duration-200"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-500">
                            <i class="fas fa-folder-open fa-3x mb-4"></i>
                            <p class="text-xl">No leads found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $leads->links() }}
    </div>
</div>
@endsection
