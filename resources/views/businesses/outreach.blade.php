
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Outreach to ') . $business->name }}
        </h2>
    </x-slot>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <form action="{{ route('businesses.outreach.send', $business->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                    <input type="text" name="subject" id="subject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ $subject }}" required>
                </div>
                <div class="mb-6">
                    <label for="body" class="block text-sm font-medium text-gray-700">Body</label>
                    <textarea name="body" id="body" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $body }}</textarea>
                </div>
                <div class="flex justify-end">
                    <a href="{{ url()->previous() }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg transition duration-200 mr-2">Cancel</a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">Send Outreach</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
