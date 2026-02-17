<x-app-layout>
    <x-slot name="header">
        {{ __('Settings · Lead Sources') }}
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold">Lead Sources</h2>
                    <p class="text-sm text-gray-500">Specific sources like Website Form, Google Ads, Webhook, etc.</p>
                </div>
                <a href="{{ route('settings.lead-sources.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Add Source
                </a>
            </div>

            <div class="flex gap-3 mb-6">
                <a href="{{ route('settings.lead-source-categories.index') }}" class="px-3 py-2 bg-gray-200 text-gray-900 rounded-md text-sm">Categories</a>
                <a href="{{ route('settings.lead-sources.index') }}" class="px-3 py-2 bg-gray-900 text-white rounded-md text-sm">Sources</a>
            </div>

            @if (session('success'))
                <div class="mb-4 p-3 rounded bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
            @endif

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($sources as $src)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $src->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $src->category?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $src->type }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $src->slug }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $src->is_active ? 'Yes' : 'No' }}</td>
                            <td class="px-4 py-3 text-right text-sm">
                                <a href="{{ route('settings.lead-sources.edit', $src) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('settings.lead-sources.destroy', $src) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Delete this source?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-sm text-gray-500">No sources yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

