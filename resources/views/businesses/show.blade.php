@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">{{ $business->name }}</h1>
            <a href="{{ route('businesses.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-700"><strong>Description:</strong> {{ $business->description }}</p>
                <p class="text-gray-700"><strong>WhatsApp Number:</strong> {{ $business->whatsapp_number }}</p>
                <p class="text-gray-700"><strong>Contact Email:</strong> {{ $business->contact_email }}</p>
                <p class="text-gray-700"><strong>Target Audience:</strong> {{ $business->target_audience }}</p>
            </div>
            <div class="flex justify-end items-center">
                <a href="{{ route('businesses.outreach', $business->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Start Outreach</a>
                <a href="{{ route('leads.create', ['business_id' => $business->id]) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-4">Add New Lead</a>
            </div>
        </div>

        <hr class="my-8">

        <h2 class="text-2xl font-bold mb-4">Leads</h2>

        @if ($message = Session::get('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ $message }}</span>
            </div>
        @endif

        <div class="bg-white shadow-md rounded my-6">
            <table class="min-w-max w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Name</th>
                        <th class="py-3 px-6 text-left">Designation</th>
                        <th class="py-3 px-6 text-center">Company Name</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach ($leads as $lead)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="font-medium">{{ $lead->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-left">
                                <div class="flex items-center">
                                    <span>{{ $lead->designation }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <span>{{ $lead->company_name }}</span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <form action="{{ route('leads.update.status', $lead->id) }}" method="POST">
                                    @csrf
                                    <select name="status" class="bg-purple-200 text-purple-600 py-1 px-3 rounded-full text-xs" onchange="this.form.submit()">
                                        <option value="New" {{ $lead->status == 'New' ? 'selected' : '' }}>New</option>
                                        <option value="Contacted" {{ $lead->status == 'Contacted' ? 'selected' : '' }}>Contacted</option>
                                        <option value="Replied" {{ $lead->status == 'Replied' ? 'selected' : '' }}>Replied</option>
                                    </select>
                                </form>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <form action="{{ route('leads.destroy', $lead->id) }}" method="POST">
                                    <a href="{{ route('leads.show', $lead->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Show</a>
                                    <a href="{{ route('leads.edit', $lead->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
