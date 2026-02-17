<x-app-layout>
    <x-slot name="header">
        {{ __('Settings · New Lead Source Category') }}
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('settings.lead-source-categories.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required />
                    @error('name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Slug (optional)</label>
                    <input name="slug" value="{{ old('slug') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                    <p class="text-xs text-gray-500 mt-1">Empty chhodo to auto-generate ho jayega.</p>
                    @error('slug')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description (optional)</label>
                    <textarea name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" rows="3">{{ old('description') }}</textarea>
                    @error('description')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Color</label>
                        <input name="color" value="{{ old('color', '#3B82F6') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                        @error('color')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                        @error('sort_order')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="flex items-center gap-2 mt-6">
                        <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', true)) />
                        <label for="is_active" class="text-sm text-gray-700">Active</label>
                        @error('is_active')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="flex gap-3">
                    <x-primary-button>Create</x-primary-button>
                    <a href="{{ route('settings.lead-source-categories.index') }}" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

