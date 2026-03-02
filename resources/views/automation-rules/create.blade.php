<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Automation Rule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-6">Create New Automation Rule</h3>

                    <form action="{{ route('automation-rules.store') }}" method="POST">
                        @csrf

                        <!-- Rule Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Rule Name</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>

                        <!-- Trigger Source -->
                        <div class="mb-4">
                            <label for="trigger_source" class="block text-sm font-medium text-gray-700">When this happens...</label>
                            <select name="trigger_source" id="trigger_source" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="lead">Lead</option>
                                <!-- Add other trigger sources here -->
                            </select>
                        </div>

                        <!-- Trigger Event -->
                        <div class="mb-4">
                            <label for="trigger_event" class="block text-sm font-medium text-gray-700">...and this event occurs...</label>
                            <select name="trigger_event" id="trigger_event" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="created">Is Created</option>
                                <option value="updated">Is Updated</option>
                                <option value="deleted">Is Deleted</option>
                                <!-- Add other trigger events here -->
                            </select>
                        </div>

                        <!-- Action Type -->
                        <div class="mb-4">
                            <label for="action_type" class="block text-sm font-medium text-gray-700">...then do this action.</label>
                            <select name="action_type" id="action_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="send_email">Send Email</option>
                                <option value="add_tag">Add Tag</option>
                                <!-- Add other action types here -->
                            </select>
                        </div>

                        <!-- Action Data -->
                        <div class="mb-4">
                            <label for="action_data" class="block text-sm font-medium text-gray-700">Action Details (JSON)</label>
                            <textarea name="action_data" id="action_data" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            <p class="mt-2 text-sm text-gray-500">Provide action-specific data in JSON format. For example: {"subject":"Welcome Email","body":"Hello!"}</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('automation-rules.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Create Rule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
