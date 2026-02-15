<x-layouts.admin>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Checkout') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6 sm:px-20 bg-white">
          <div class="mt-6">
            <!-- Plan Summary -->
            <div class="mb-8">
              <h3 class="text-lg font-medium text-gray-900">Order Summary</h3>
              <div class="mt-4 bg-gray-50 rounded-lg p-6">
                <div class="flex justify-between items-center">
                  <div>
                    <h4 class="text-lg font-semibold">{{ $plan->name }} Plan</h4>
                    <p class="text-gray-600">${{ number_format($plan->price, 2) }}/month</p>
                  </div>
                  <a href="{{ route('subscription.index') }}" class="text-indigo-600 hover:text-indigo-900">Change</a>
                </div>
                <div class="mt-4">
                  <h5 class="text-sm font-medium text-gray-700">Included Features:</h5>
                  <ul class="mt-2 space-y-2">
                    @if($plan->team_members)
                    <li class="text-sm text-gray-600 flex items-center">
                      <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      {{ $plan->team_members }} Team Members
                    </li>
                    @endif
                    @if($plan->storage)
                    <li class="text-sm text-gray-600 flex items-center">
                      <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      {{ $plan->storage / 1024 }}GB Storage
                    </li>
                    @endif
                    @if($plan->analytics)
                    <li class="text-sm text-gray-600 flex items-center">
                      <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      Analytics Included
                    </li>
                    @endif
                    @if($plan->api_access)
                    <li class="text-sm text-gray-600 flex items-center">
                      <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      API Access
                    </li>
                    @endif
                  </ul>
                </div>
              </div>
            </div>

            <!-- Payment Form -->
            <div>
              <h3 class="text-lg font-medium text-gray-900">Payment Method</h3>
              <form action="{{ route('subscription.process') }}" method="POST" id="payment-form" class="mt-4">
                @csrf
                <input type="hidden" name="plan" value="{{ $plan->slug }}">

                <div class="col-span-6 sm:col-span-4 mb-4">
                  <label for="card-holder-name" class="block text-sm font-medium text-gray-700">Card holder name</label>
                  <input type="text" name="card-holder-name" id="card-holder-name"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    required>
                </div>

                <div class="col-span-6 sm:col-span-4 mb-4">
                  <label for="card-element" class="block text-sm font-medium text-gray-700">Credit or debit card</label>
                  <div id="card-element" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-4">
                    <!-- Stripe Elements Placeholder -->
                  </div>
                  <div id="card-errors" role="alert" class="mt-2 text-red-600 text-sm"></div>
                </div>

                <div class="mt-6">
                  {{-- <button type="submit" id="card-button" data-secret="{{ $intent->client_secret }}"
                    class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Subscribe Now
                  </button> --}}
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script src="https://js.stripe.com/v3/"></script>
  <script>
    const stripe = Stripe('{{ config('cashier.key') }}');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const cardHolderName = document.getElementById('card-holder-name');
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;
        const form = document.getElementById('payment-form');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            cardButton.disabled = true;
            
            const { setupIntent, error } = await stripe.confirmCardSetup(
                clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: { name: cardHolderName.value }
                    }
                }
            );

            if (error) {
                cardButton.disabled = false;
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
            } else {
                const paymentMethodInput = document.createElement('input');
                paymentMethodInput.setAttribute('type', 'hidden');
                paymentMethodInput.setAttribute('name', 'payment_method');
                paymentMethodInput.setAttribute('value', setupIntent.payment_method);
                form.appendChild(paymentMethodInput);
                form.submit();
            }
        });
  </script>
  @endpush
</x-layouts.admin>