<!-- Validation Errors -->
<x-validation-errors class="mb-4" :errors="$errors" />

<form method="POST" action="{{ $action }}">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <!-- Rule Name -->
    <div>
        <x-label for="name" :value="__('Rule Name')" />
        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $rule->name)" required autofocus />
    </div>

    <!-- Condition Field -->
    <div class="mt-4">
        <x-label for="condition_field" :value="__('Condition Field')" />
        <x-input id="condition_field" class="block mt-1 w-full" type="text" name="condition_field" :value="old('condition_field', $rule->condition_field)" required />
        <p class="mt-2 text-sm text-gray-500">The lead attribute to check (e.g., 'source', 'status').</p>
    </div>

    <!-- Condition Value -->
    <div class="mt-4">
        <x-label for="condition_value" :value="__('Condition Value')" />
        <x-input id="condition_value" class="block mt-1 w-full" type="text" name="condition_value" :value="old('condition_value', $rule->condition_value)" required />
        <p class="mt-2 text-sm text-gray-500">The value the field should have (e.g., 'Website Form', 'verified').</p>
    </div>

    <!-- Points -->
    <div class="mt-4">
        <x-label for="points" :value="__('Points')" />
        <x-input id="points" class="block mt-1 w-full" type="number" name="points" :value="old('points', $rule->points)" required />
    </div>

    <div class="flex items-center justify-end mt-4">
        <a href="{{ route('scoring-rules.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900">
            {{ __('Cancel') }}
        </a>

        <x-button class="ml-4">
            {{ $buttonLabel }}
        </x-button>
    </div>
</form>
