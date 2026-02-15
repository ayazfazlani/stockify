<x-layouts.admin>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6">
          @php
          $currentPlan = $plans->firstWhere('slug', $tenant->subscription_plan);
          @endphp
          <h2 class="text-2xl font-bold mb-4">Subscribe to {{ $currentPlan?->name ?? 'Plan' }}</h2>

          @if (session('error'))
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
          </div>
          @endif

          <form action="{{ route('subscription.subscribe') }}" method="POST" id="payment-form">
            @csrf
            <input type="hidden" name="plan" value="{{ $tenant->subscription_plan ?? '' }}">

            <div class="mb-4">
              <label for="card-holder-name" class="block text-sm font-medium text-gray-700">Card Holder Name</label>
              <input type="text" id="card-holder-name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div class="mb-4">
              <label for="card-element" class="block text-sm font-medium text-gray-700">Credit or debit card</label>
              <div id="card-element" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
              <div id="card-errors" class="mt-2 text-red-600 text-sm" role="alert"></div>
            </div>

            <button type="submit" id="card-button" data-secret="{{ $intent->client_secret }}"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
              Subscribe Now
            </button>
          </form>
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

        cardElement.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            const { setupIntent, error } = await stripe.confirmCardSetup(
                clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: { name: cardHolderName.value }
                    }
                }
            );

            if (error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
            } else {
                const hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'payment_method');
                hiddenInput.setAttribute('value', setupIntent.payment_method);
                form.appendChild(hiddenInput);

                form.submit();
            }
        });
  </script>
  @endpush
</x-layouts.admin>