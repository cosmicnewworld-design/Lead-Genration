<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Campaign Step for {{ $campaign->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('campaigns.steps.store', $campaign) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-input mt-1 block w-full" value="{{ old('subject') }}" required autofocus>
                        </div>

                        <div class="mb-4">
                            <label for="body" class="block text-sm font-medium text-gray-700">Body</label>
                            <textarea name="body" id="body" class="form-textarea mt-1 block w-full" rows="6" required>{{ old('body') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="delay_in_days" class="block text-sm font-medium text-gray-700">Delay (in days)</label>
                            <input type="number" name="delay_in_days" id="delay_in_days" class="form-input mt-1 block w-full" value="{{ old('delay_in_days') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="order" class="block text-sm font-medium text-gray-700">Order</label>
                            <input type="number" name="order" id="order" class="form-input mt-1 block w-full" value="{{ old('order') }}" required>
                        </div>

                        <x-primary-button>
                            Create Step
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
