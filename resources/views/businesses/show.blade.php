
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $business->name }}
            </h2>
            <a href="{{ route('businesses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg transition duration-200">
                Back to Businesses
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Business Details</h3>
                    <p class="text-gray-600 mb-4">{{ $business->description }}</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Contact Email</p>
                            <p class="text-lg font-medium text-gray-800">{{ $business->contact_email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">WhatsApp Number</p>
                            <p class="text-lg font-medium text-gray-800">{{ $business->whatsapp_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Target Audience</p>
                            <p class="text-lg font-medium text-gray-800">{{ $business->target_audience }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-6 border-t border-gray-200 flex justify-end">
                    <a href="{{ route('businesses.outreach', $business->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-lg transition duration-200 mr-2">Start Outreach</a>
                    <a href="{{ route('leads.create', ['business_id' => $business->id]) }}" class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-bold rounded-lg transition duration-200">+ Add New Lead</a>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">At a Glance</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="bg-green-500 p-3 rounded-full text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.25-1.264-.7-1.732M7 20v-2c0-.653.25-1.264.7-1.732M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-1.105.895-2 2-2h6c1.105 0 2 .895 2 2v2M9 7h6m-6 4h6"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Total Leads</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $business->leads->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Leads</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Designation</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($business->leads as $lead)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $lead->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $lead->designation }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $lead->company_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $lead->status == 'Verified' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $lead->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('leads.show', $lead->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">View</a>
                                    <a href="{{ route('leads.edit', $lead->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-4">Edit</a>
                                    <form action="{{ route('leads.destroy', $lead->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">No leads found for this business.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
