<div>
  <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
      <div class="p-6">
        <!-- Progress Bar -->
        <div class="mb-8">
          <div class="flex justify-between mb-2">
            @for ($i = 1; $i <= $totalSteps; $i++) <div class="flex items-center">
              <div
                class="@if($step >= $i) bg-indigo-600 @else bg-gray-200 @endif w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold">
                {{ $i }}
              </div>
              @if ($i < $totalSteps) <div
                class="@if($step > $i) bg-indigo-600 @else bg-gray-200 @endif h-1 w-16 mx-2 mt-4">
          </div>
          @endif
        </div>
        @endfor
      </div>
    </div>

    <!-- Step Content -->
    <div class="mt-8">
      @if ($step === 1)
      <div>
        <h3 class="text-lg font-medium text-gray-900">Team Profile</h3>
        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
          <div class="sm:col-span-4">
            <label for="teamName" class="block text-sm font-medium text-gray-700">Team Name</label>
            <div class="mt-1">
              <input type="text" wire:model="teamName" id="teamName"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
            </div>
          </div>

          <div class="sm:col-span-3">
            <label for="industry" class="block text-sm font-medium text-gray-700">Industry</label>
            <div class="mt-1">
              <select wire:model="industry" id="industry"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                <option value="">Select Industry</option>
                <option value="retail">Retail</option>
                <option value="manufacturing">Manufacturing</option>
                <option value="distribution">Distribution</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>

          <div class="sm:col-span-3">
            <label for="size" class="block text-sm font-medium text-gray-700">Team Size</label>
            <div class="mt-1">
              <select wire:model="size" id="size"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                <option value="">Select Size</option>
                <option value="1-10">1-10 employees</option>
                <option value="11-50">11-50 employees</option>
                <option value="51-200">51-200 employees</option>
                <option value="201+">201+ employees</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      @elseif ($step === 2)
      <div>
        <h3 class="text-lg font-medium text-gray-900">Choose Your Plan</h3>
        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-3">
          @foreach(['starter', 'professional', 'enterprise'] as $planOption)
          <div
            class="relative bg-white border rounded-lg shadow-sm p-6 cursor-pointer @if($plan === $planOption) border-indigo-500 ring-2 ring-indigo-500 @else border-gray-300 @endif"
            wire:click="$set('plan', '{{ $planOption }}')">
            <div class="flex items-center justify-between">
              <div>
                <h4 class="text-sm font-medium text-gray-900">{{ ucfirst($planOption) }}</h4>
                <p class="mt-1 text-sm text-gray-500">
                  {{ $planOption === 'starter' ? 'Perfect for small teams' :
                  ($planOption === 'professional' ? 'For growing businesses' : 'Custom solutions') }}
                </p>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @elseif ($step === 3)
      <div>
        <h3 class="text-lg font-medium text-gray-900">Initial Settings</h3>
        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
          <div class="sm:col-span-3">
            <label for="defaultCurrency" class="block text-sm font-medium text-gray-700">Default Currency</label>
            <div class="mt-1">
              <select wire:model="defaultCurrency" id="defaultCurrency"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                <option value="">Select Currency</option>
                <option value="USD">USD - US Dollar</option>
                <option value="EUR">EUR - Euro</option>
                <option value="GBP">GBP - British Pound</option>
              </select>
            </div>
          </div>

          <div class="sm:col-span-3">
            <label for="timezone" class="block text-sm font-medium text-gray-700">Timezone</label>
            <div class="mt-1">
              <select wire:model="timezone" id="timezone"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                <option value="">Select Timezone</option>
                <option value="UTC">UTC</option>
                <option value="America/New_York">Eastern Time</option>
                <option value="America/Chicago">Central Time</option>
                <option value="America/Denver">Mountain Time</option>
                <option value="America/Los_Angeles">Pacific Time</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      @else
      <div>
        <h3 class="text-lg font-medium text-gray-900">Ready to Go!</h3>
        <div class="mt-6">
          <p class="text-sm text-gray-500">
            Congratulations! Your team is now set up and ready to start using {{ config('app.name') }}.
            Click the button below to access your dashboard.
          </p>
        </div>
      </div>
      @endif
    </div>

    <!-- Navigation Buttons -->
    <div class="mt-8 flex justify-between">
      @if ($step > 1)
      <button wire:click="previousStep" type="button"
        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        Previous
      </button>
      @else
      <div></div>
      @endif

      @if ($step < $totalSteps) <button wire:click="nextStep" type="button"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        Next
        </button>
        @else
        <button wire:click="completeOnboarding" type="button"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          Get Started
        </button>
        @endif
    </div>
  </div>
</div>
</div>
</div>