
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leads') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-semibold text-gray-700">Your Leads</h3>
                        <a href="{{ route('leads.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Create Lead') }}
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="flex space-x-4 mb-4">
                        <a href="{{ route('leads.index') }}" class="text-indigo-600 hover:text-indigo-900">All</a>
                        <a href="{{ route('segments.show', 'Hot') }}" class="text-red-600 hover:text-red-900">Hot</a>
                        <a href="{{ route('segments.show', 'Warm') }}" class="text-yellow-600 hover:text-yellow-900">Warm</a>
                        <a href="{{ route('segments.show', 'Cold') }}" class="text-blue-600 hover:text-blue-900">Cold</a>
                    </div>

                    <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr class="text-left">
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Segment</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($leads as $lead)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('leads.show', $lead) }}" class="text-indigo-600 hover:text-indigo-900">{{ $lead->name }}</a>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $lead->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $lead->source ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $lead->score }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $lead->segment }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $lead->created_at->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('leads.edit', $lead) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="inline-block ml-4">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this lead?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            No leads in this segment.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
