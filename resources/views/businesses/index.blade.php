
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Businesses') }}
            </h2>
            <a href="{{ route('businesses.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-lg transition duration-200">
                + Add New Business
            </a>
        </div>
    </x-slot>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target Audience</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($businesses as $business)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $business->name }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($business->description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $business->contact_email }}</div>
                                    <div class="text-sm text-gray-500">{{ $business->whatsapp_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $business->target_audience }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('businesses.outreach', $business->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Outreach</a>
                                    <a href="{{ route('businesses.show', $business->id) }}" class="text-gray-600 hover:text-gray-900 mr-4">View</a>
                                    <a href="{{ route('businesses.edit', $business->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-4">Edit</a>
                                    <form action="{{ route('businesses.destroy', $business->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    <div class="text-center py-10">
                                        <h3 class="text-lg font-medium text-gray-900">No Businesses Found</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new business.</p>
                                        <div class="mt-6">
                                            <a href="{{ route('businesses.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">+ New Business</a>
                                        </div>
                                    </div>
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
    </div>
</x-app-layout>
