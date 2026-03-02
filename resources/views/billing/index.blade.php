<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Billing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    @if ($currentSubscription)
                        <div class="text-center">
                            <h1 class="text-4xl font-bold text-gray-800">Your Current Plan</h1>
                            <p class="text-gray-600 mt-2">You are currently subscribed to the {{ $currentSubscription->name }} plan.</p>

                            <div class="mt-8">
                                @if ($currentSubscription->onGracePeriod())
                                    <p class="text-yellow-600">Your subscription is in a grace period and will end on {{ $currentSubscription->ends_at->format('F j, Y') }}.</p>
                                    <form action="{{ route('subscriptions.resume') }}" method="POST" class="mt-4">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            Resume Subscription
                                        </button>
                                    </form>
                                @elseif ($currentSubscription->active())
                                    <p class="text-green-600">Your subscription is active.</p>
                                    <form action="{{ route('subscriptions.cancel') }}" method="POST" class="mt-4">
                                        @csrf
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                            Cancel Subscription
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('subscriptions.portal') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-900">
                                    Access Billing Portal
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            <h1 class="text-4xl font-bold text-gray-800">Our Pricing Plans</h1>
                            <p class="text-gray-600 mt-2">Choose the plan that's right for your business.</p>
                        </div>

                        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach ($plans as $plan)
                                <div class="bg-gray-50 p-8 rounded-lg shadow-md">
                                    <h2 class="text-2xl font-bold text-gray-800">{{ $plan->name }}</h2>
                                    <p class="text-gray-600 mt-2">{{ $plan->description }}</p>
                                    <div class="mt-6">
                                        <span class="text-4xl font-bold">${{ $plan->price }}</span>
                                        <span class="text-gray-600">/ month</span>
                                    </div>
                                    <a href="{{ route('billing.checkout', $plan->slug) }}" class="mt-8 block w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center font-bold py-2 px-4 rounded">
                                        Subscribe
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
