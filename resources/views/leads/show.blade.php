<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $lead->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-700">Lead Details</h3>
                        <p><strong>Name:</strong> {{ $lead->name }}</p>
                        <p><strong>Email:</strong> {{ $lead->email }}</p>
                        <p><strong>Source:</strong> {{ $lead->source }}</p>
                        <p><strong>Score:</strong> {{ $lead->score }}</p>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-700">Campaigns</h3>
                        <ul>
                            @foreach ($lead->campaigns as $campaign)
                                <li>{{ $campaign->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
