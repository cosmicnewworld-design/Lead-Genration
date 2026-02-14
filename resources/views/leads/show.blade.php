<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $lead->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Lead Details</h3>
                            <div class="mt-4 space-y-2">
                                <p><strong>Name:</strong> {{ $lead->name }}</p>
                                <p><strong>Email:</strong> {{ $lead->email }}</p>
                                <p><strong>Source:</strong> {{ $lead->source ?? 'N/A' }}</p>
                                <p><strong>Created At:</strong> {{ $lead->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Lead Score</h3>
                            <div class="mt-4 space-y-2">
                                <p class="text-3xl font-bold text-gray-800">{{ $lead->score }}</p>
                                <p><strong>Segment:</strong> {{ $lead->segment }}</p>
                                <div class="mt-4">
                                    <h4 class="text-md font-medium text-gray-700">Score Breakdown:</h4>
                                    <ul class="list-disc list-inside mt-2 space-y-1">
                                        @forelse ($scoreBreakdown as $breakdown)
                                            <li>{{ $breakdown['description'] }}</li>
                                        @empty
                                            <li>No scoring rules matched.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
