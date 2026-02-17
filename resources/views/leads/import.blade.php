<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Leads (CSV)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('leads.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700">CSV File</label>
                            <input type="file" name="file" id="file" accept=".csv,text/csv" class="mt-1 block w-full">
                            <p class="mt-1 text-xs text-gray-500">Headers supported: name,email,phone,company,job_title,website,source</p>
                        </div>

                        <div class="mb-4">
                            <label for="lead_source_id" class="block text-sm font-medium text-gray-700">Lead Source</label>
                            <select name="lead_source_id" id="lead_source_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select source</option>
                                @foreach ($leadSources as $categoryName => $sources)
                                    <optgroup label="{{ $categoryName }}">
                                        @foreach ($sources as $src)
                                            <option value="{{ $src->id }}">{{ $src->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <input type="text" name="source" id="source" placeholder="Custom source (optional)" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Import
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

