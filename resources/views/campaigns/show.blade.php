<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $campaign->name }}
            </h2>
            <a href="{{ route('campaigns.steps.create', $campaign) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Step
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Campaign Details</h3>
                    <p><strong>Name:</strong> {{ $campaign->name }}</p>

                    <h3 class="text-lg font-semibold mt-6 mb-4">Campaign Steps</h3>
                    @if($campaign->steps->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Order
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subject
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Delay (Days)
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($campaign->steps as $step)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $step->order }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $step->subject }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $step->delay_in_days }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('steps.edit', $step) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('steps.destroy', $step) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>This campaign has no steps.</p>
                    @endif

                    <h3 class="text-lg font-semibold mt-6 mb-4">Start Campaign with Leads</h3>
                    <form action="{{ route('campaigns.start', $campaign) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Select Leads</label>
                            @foreach($leads as $lead)
                                <div class="flex items-center">
                                    <input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}" class="form-checkbox h-5 w-5 text-gray-600"><span class="ml-2 text-gray-700">{{ $lead->name }} ({{ $lead->email }})</span>
                                </div>
                            @endforeach
                        </div>
                        <x-primary-button>
                            Start Campaign
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
