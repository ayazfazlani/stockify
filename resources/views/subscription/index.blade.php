<x-layouts.admin>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6">
          <h2 class="text-2xl font-bold mb-4">Choose Your Plan</h2>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($plans as $plan)
            <div
              class="border rounded-lg p-6 {{ $tenant->subscription_plan === $plan->slug ? 'border-blue-500' : '' }}">
              <h3 class="text-xl font-semibold mb-2">{{ $plan->name }}</h3>
              <p class="text-3xl font-bold mb-4">${{ number_format($plan->price, 2) }}<span
                  class="text-gray-500 text-sm">/month</span></p>

              <ul class="space-y-2 mb-6">
                <li>
                  <i class="fas fa-users mr-2"></i>
                  {{ $plan->team_members ?? 'Unlimited' }} Team Members
                </li>
                <li>
                  <i class="fas fa-hdd mr-2"></i>
                  {{ ($plan->storage ?? 0) / 1024 }}GB Storage
                </li>
                <li>
                  <i class="fas fa-chart-line mr-2"></i>
                  {{ $plan->analytics ? 'Analytics Included' : 'Basic Analytics' }}
                </li>
                <li>
                  <i class="fas fa-code mr-2"></i>
                  {{ $plan->api_access ? 'API Access' : 'No API Access' }}
                </li>
              </ul>

              @if($tenant->subscription_plan === $plan->slug )
              <button disabled class="w-full bg-blue-500 text-white rounded-md py-2 px-4">
                Current Plan
              </button>
              @else
              <a href="{{ route('subscription.checkout', ['plan' => $plan->slug]) }}"
                class="block text-center w-full bg-blue-600 hover:bg-blue-700 text-white rounded-md py-2 px-4">
                Select Plan
              </a>
              @endif
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</x-layouts.admin>