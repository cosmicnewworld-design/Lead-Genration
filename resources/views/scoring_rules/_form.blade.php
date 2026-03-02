<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Rule Name -->
    <div>
        <x-input-label for="name" :value="__('Rule Name')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $rule->name ?? '')" required autofocus />
    </div>

    <!-- Points -->
    <div>
        <x-input-label for="points" :value="__('Points')" />
        <x-text-input id="points" class="block mt-1 w-full" type="number" name="points" :value="old('points', $rule->points ?? '')" required />
    </div>

    <!-- Condition Field -->
    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
        <div>
            <x-input-label for="condition_field" :value="__('If this Field...')" />
            <x-text-input id="condition_field" class="block mt-1 w-full" type="text" name="condition_field" :value="old('condition_field', $rule->condition_field ?? '')" required placeholder="e.g., source or status"/>
        </div>

        <!-- Operator -->
        <div>
            <x-input-label for="operator" :value="__('...is...')" />
            <select name="operator" id="operator" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @foreach($operators as $operatorKey => $operatorLabel)
                    <option value="{{ $operatorKey }}" {{ (old('operator', $rule->operator ?? 'equals') == $operatorKey) ? 'selected' : '' }}>
                        {{ $operatorLabel }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Condition Value -->
        <div>
            <x-input-label for="condition_value" :value="__('...this Value')" />
            <x-text-input id="condition_value" class="block mt-1 w-full" type="text" name="condition_value" :value="old('condition_value', $rule->condition_value ?? '')" required placeholder="e.g., Website or Verified"/>
        </div>
    </div>
</div>
