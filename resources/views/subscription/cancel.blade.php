<x-layouts.app>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6 text-center">
          <svg class="mx-auto h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>

          <h2 class="mt-4 text-2xl font-bold">Subscription Cancelled!</h2>
          <p class="mt-2 text-gray-600">Your subscription has been cancelled.</p>

          <div class="mt-6">
            {{-- <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">
              Return to Dashboard
            </a> --}}
          </div>
        </div>
      </div>
    </div>
  </div>
</x-layouts.app>