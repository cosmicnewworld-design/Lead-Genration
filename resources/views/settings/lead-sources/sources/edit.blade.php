<x-app-layout>
    <x-slot name="header">
        {{ __('Settings · Edit Lead Source') }}
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('settings.lead-sources.update', $source) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input name="name" value="{{ old('name', $source->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required />
                    @error('name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Slug</label>
                    <input name="slug" value="{{ old('slug', $source->slug) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                    @error('slug')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category (optional)</label>
                        <select name="lead_source_category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">—</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected((int) old('lead_source_category_id', $source->lead_source_category_id) === $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('lead_source_category_id')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        @php($type = old('type', $source->type))
                        <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            <option value="manual" @selected($type === 'manual')>manual</option>
                            <option value="import" @selected($type === 'import')>import</option>
                            <option value="api" @selected($type === 'api')>api</option>
                            <option value="webhook" @selected($type === 'webhook')>webhook</option>
                            <option value="ads" @selected($type === 'ads')>ads</option>
                            <option value="scraper" @selected($type === 'scraper')>scraper</option>
                            <option value="referral" @selected($type === 'referral')>referral</option>
                        </select>
                        @error('type')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $source->sort_order) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                        @error('sort_order')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="flex items-center gap-2 mt-6">
                        <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $source->is_active)) />
                        <label for="is_active" class="text-sm text-gray-700">Active</label>
                        @error('is_active')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="flex gap-3">
                    <x-primary-button>Save</x-primary-button>
                    <a href="{{ route('settings.lead-sources.index') }}" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

