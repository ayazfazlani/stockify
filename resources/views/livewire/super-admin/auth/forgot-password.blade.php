<div>
  <div class="sm:mx-auto sm:w-full sm:max-w-md">
    <img class="mx-auto h-12 w-auto" src="{{ asset('logo.png') }}" alt="Stockify">
    <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Reset Password</h2>
    <p class="mt-2 text-center text-sm text-gray-600">
      Enter your email address and we'll send you a link to reset your password.
    </p>
  </div>

  <div class="mt-8">
    @if($emailSent)
    <div class="rounded-md bg-green-50 p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
              clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-green-800">
            Reset link has been sent to your email address.
          </p>
        </div>
      </div>
    </div>
    @endif

    @if($error)
    <div class="rounded-md bg-red-50 p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
              clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-red-800">{{ $error }}</p>
        </div>
      </div>
    </div>
    @endif

    <form wire:submit.prevent="sendResetLink" class="space-y-6">
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
        <div class="mt-1">
          <input wire:model="email" id="email" type="email" autocomplete="email" required
            class="block w-full appearance-none rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
          @error('email') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
        </div>
      </div>

      <div>
        <button type="submit"
          class="flex w-full justify-center rounded-lg border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
          Send Reset Link
        </button>
      </div>

      <div class="text-sm text-center">
        <a href="{{ route('super-admin.login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
          Back to login
        </a>
      </div>
    </form>
  </div>
</div>