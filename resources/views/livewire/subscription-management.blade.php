<div>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Current Subscription -->
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
        <div class="p-6">
          <h2 class="text-2xl font-bold mb-4">Current Subscription</h2>

          @if($team->subscription('default'))
          <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <div class="flex justify-between items-center">
              <div>
                <h3 class="text-lg font-semibold">{{ $currentPlan['name'] }} Plan</h3>
                <p class="text-gray-600">${{ number_format($currentPlan['price'], 2) }}/month</p>

                @if($team->subscription('default')->onGracePeriod())
                <p class="text-yellow-600">
                  Your subscription will end on {{ $team->subscription('default')->ends_at->format('F j, Y') }}
                </p>
                @endif
              </div>

              <div>
                @if($team->subscription('default')->onGracePeriod())
                <button wire:click="resumeSubscription" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                  Resume Subscription
                </button>
                @else
                <button wire:click="confirmCancel" class="bg-red-500 text-white px-4 py-2 rounded-md">
                  Cancel Subscription
                </button>
                @endif
              </div>
            </div>
          </div>

          <!-- Features -->
          <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Your Features</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              @foreach($currentPlan['features'] as $feature => $value)
              <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>
                  @if(is_bool($value))
                  {{ ucfirst(str_replace('_', ' ', $feature)) }}
                  @else
                  {{ ucfirst(str_replace('_', ' ', $feature)) }}: {{ is_numeric($value) ? number_format($value) : $value
                  }}
                  @endif
                </span>
              </div>
              @endforeach
            </div>
          </div>
          @else
          <p class="text-gray-600">You don't have an active subscription.</p>
          <a href="{{ route('subscription.index') }}"
            class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded-md">
            Choose a Plan
          </a>
          @endif
        </div>
      </div>

      <!-- Billing History -->
      @if(count($invoices) > 0)
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6">
          <h2 class="text-2xl font-bold mb-4">Billing History</h2>

          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @foreach($invoices as $invoice)
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap">
                    {{ $invoice->date()->format('F j, Y') }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    ${{ number_format($invoice->total() / 100, 2) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                      {{ ucfirst($invoice->status) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <button wire:click="downloadInvoice('{{ $invoice->id }}')"
                      class="text-blue-600 hover:text-blue-900">
                      Download
                    </button>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>

  <!-- Cancel Subscription Modal -->
  @if($showCancelModal)
  <div class="fixed z-10 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 transition-opacity" aria-hidden="true">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div>

      <div
        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mt-3 text-center sm:mt-0 sm:text-left">
              <h3 class="text-lg leading-6 font-medium text-gray-900">
                Cancel Subscription
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  Are you sure you want to cancel your subscription? You will continue to have access until the end of
                  your current billing period.
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button wire:click="cancelSubscription" type="button"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
            Yes, Cancel
          </button>
          <button wire:click="$set('showCancelModal', false)" type="button"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Keep Subscription
          </button>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>