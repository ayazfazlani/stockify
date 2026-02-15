<div>
    @if($errorMessage)
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
        {{ $errorMessage }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($plans as $plan)
        <div
            class="border rounded-lg p-6 {{ $selectedPlan == $plan->id ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-200' }}">
            <div class="text-center">
                <h3 class="text-xl font-bold">{{ $plan->name }}</h3>
                <div class="my-4">
                    <span class="text-3xl font-bold">${{ $plan->price }}</span>
                    <span class="text-gray-600">/{{ $plan->interval }}</span>
                </div>

                @if($plan->trial_days > 0)
                <div class="mb-4">
                    <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">
                        {{ $plan->trial_days }} day{{ $plan->trial_days > 1 ? 's' : '' }} free trial
                    </span>
                </div>
                @endif

                <ul class="text-left mb-6 space-y-2">
                    @foreach(json_decode($plan->features) as $feature)
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>

                <button wire:click="$set('selectedPlan', {{ $plan->id }})"
                    class="w-full px-4 py-2 rounded border {{ $selectedPlan == $plan->id ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 text-gray-800 border-gray-300 hover:bg-gray-200' }}">
                    {{ $selectedPlan == $plan->id ? '✓ Selected' : 'Select Plan' }}
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8 text-center">
        <button wire:click="subscribe" wire:loading.attr="disabled" wire:target="subscribe"
            class="px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 disabled:opacity-50">
            <span wire:loading.remove wire:target="subscribe">
                Continue to Payment
            </span>
            <span wire:loading wire:target="subscribe">
                Processing...
            </span>
        </button>
        <p class="text-gray-600 text-sm mt-2">
            You'll be redirected to Stripe for secure payment
        </p>
    </div>
</div>