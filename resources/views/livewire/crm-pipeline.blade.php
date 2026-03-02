<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">

    @foreach($statuses as $status)
        <div class="bg-gray-200 rounded-lg shadow-inner">
            <div class="p-4 border-b border-gray-300">
                <h4 class="text-lg font-semibold text-gray-800">{{ ucfirst($status) }} ({{ $leadsByStatus[$status]->count() }})</h4>
            </div>

            <div class="p-4 space-y-4" wire:sortable-group.item-group="{{ $status }}">
                @if($leadsByStatus[$status]->isNotEmpty())
                    @foreach($leadsByStatus[$status] as $lead)
                        <div class="bg-white rounded-lg shadow p-4 cursor-pointer" wire:sortable-group.item="{{ $lead->id }}" wire:key="lead-{{ $lead->id }}">
                            <h5 class="font-bold text-gray-900">{{ $lead->name }}</h5>
                            <p class="text-sm text-gray-600">{{ $lead->email }}</p>
                            <div class="mt-3 flex justify-between items-center">
                                <span class="px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded-full">
                                    Score: {{ $lead->score ?? 'N/A' }}
                                </span>
                                <span class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-200 rounded-full">
                                    {{ $lead->source ?? 'Unknown' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-sm text-gray-500 p-4">No leads in this stage.</p>
                @endif
            </div>
        </div>
    @endforeach

</div>
