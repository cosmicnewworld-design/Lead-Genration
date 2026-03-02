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
                            <h3 class="text-lg font-semibold mb-2">Lead Details</h3>
                            <p><strong>Email:</strong> {{ $lead->email }}</p>
                            <p><strong>Phone:</strong> {{ $lead->phone ?? 'N/A' }}</p>
                            <p><strong>Company:</strong> {{ $lead->company ?? 'N/A' }}</p>
                            <p><strong>Job Title:</strong> {{ $lead->job_title ?? 'N/A' }}</p>
                            <p><strong>Score:</strong> {{ $lead->score }}</p>
                            <p><strong>Status:</strong> {{ $lead->status }}</p>
                            <p><strong>Source:</strong> {{ $lead->source->name ?? 'N/A' }}</p>
                            <p><strong>Created At:</strong> {{ $lead->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('leads.edit', $lead) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Lead
                        </a>
                    </div>
                </div>
            </div>

            <!-- Activities Section -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-2">Activities</h3>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Add Activity Form -->
                        <form action="{{ route('leads.activities.store', $lead) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="activity_type" class="block text-sm font-medium text-gray-700">Activity Type</label>
                                <input type="text" name="activity_type" id="activity_type" class="mt-1 block w-full" required>
                            </div>
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3" class="mt-1 block w-full" required></textarea>
                            </div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add Activity
                            </button>
                        </form>

                        <!-- Activities List -->
                        <div class="mt-6">
                            @forelse ($lead->activities as $activity)
                                <div class="border-t border-gray-200 py-4">
                                    <p class="font-semibold">{{ $activity->activity_type }}</p>
                                    <p>{{ $activity->description }}</p>
                                    <p class="text-sm text-gray-500">By {{ $activity->user->name }} on {{ $activity->created_at->format('M d, Y') }}</p>
                                </div>
                            @empty
                                <p>No activities yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attachments Section -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-2">Attachments</h3>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Add Attachment Form -->
                        <form action="{{ route('leads.attachments.store', $lead) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="file" class="block text-sm font-medium text-gray-700">File</label>
                                <input type="file" name="file" id="file" class="mt-1 block w-full" required>
                            </div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Upload Attachment
                            </button>
                        </form>

                        <!-- Attachments List -->
                        <div class="mt-6">
                            @forelse ($lead->attachments as $attachment)
                                <div class="border-t border-gray-200 py-4 flex justify-between items-center">
                                    <div>
                                        <p>{{ $attachment->file_name }}</p>
                                        <p class="text-sm text-gray-500">Uploaded by {{ $attachment->user->name }} on {{ $attachment->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div class="flex items-center">
                                        <a href="{{ route('leads.attachments.download', [$lead, $attachment]) }}" class="text-blue-500 hover:text-blue-700 mr-4">Download</a>
                                        <form action="{{ route('leads.attachments.destroy', [$lead, $attachment]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this attachment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p>No attachments yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
