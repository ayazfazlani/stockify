<x-layouts.admin>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="text-center mb-8">
        <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Choose Your Plan</h2>
        <p class="mt-4 text-lg leading-6 text-gray-600">Get started with Stockify today and scale as you grow</p>
      </div>

      <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Starter Plan -->
        <div class="rounded-lg shadow-sm ring-1 ring-gray-900/10">
          <div class="p-8">
            <h3 class="text-lg font-semibold leading-8 text-gray-900">Starter</h3>
            <p class="mt-4 text-sm leading-6 text-gray-600">Perfect for small businesses just getting started</p>
            <p class="mt-6 flex items-baseline gap-x-1">
              <span class="text-4xl font-bold tracking-tight text-gray-900">$29</span>
              <span class="text-sm font-semibold leading-6 text-gray-600">/month</span>
            </p>
            <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600">
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                    clip-rule="evenodd" />
                </svg>
                Up to 100 items
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                    clip-rule="evenodd" />
                </svg>
                5 team members
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                    clip-rule="evenodd" />
                </svg>
                Basic analytics
              </li>
            </ul>
            <a href="{{ route('subscription.checkout', ['plan' => 'starter']) }}"
              class="mt-8 block rounded-md bg-indigo-600 px-3.5 py-2 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
              Get Started
            </a>
          </div>
        </div>

        <!-- Professional Plan -->
        <div class="relative rounded-lg shadow-sm ring-2 ring-indigo-600">
          <div class="p-8">
            <h3 class="text-lg font-semibold leading-8 text-gray-900">Professional</h3>
            <p
              class="absolute -top-4 right-8 rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold leading-6 text-white">
              Most popular</p>
            <p class="mt-4 text-sm leading-6 text-gray-600">For growing businesses with advanced needs</p>
            <p class="mt-6 flex items-baseline gap-x-1">
              <span class="text-4xl font-bold tracking-tight text-gray-900">$79</span>
              <span class="text-sm font-semibold leading-6 text-gray-600">/month</span>
            </p>
            <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600">
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                    clip-rule="evenodd" />
                </svg>
                Up to 1,000 items
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                    clip-rule="evenodd" />
                </svg>
                15 team members
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                    clip-rule="evenodd" />
                </svg>
                Advanced analytics
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                    clip-rule="evenodd" />
                </svg>
                Priority support
              </li>
            </ul>
            <a href="{{ route('subscription.checkout', ['plan' => 'professional']) }}"
              class="mt-8 block rounded-md bg-indigo-600 px-3.5 py-2 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
              Get Started
            </a>
          </div>
        </div>

        <!-- Enterprise Plan -->
        <div class="rounded-lg shadow-sm ring-1 ring-gray-900/10">
          <div class="p-8">
            <h3 class="text-lg font-semibold leading-8 text-gray-900">Enterprise</h3>
            <p class="mt-4 text-sm leading-6 text-gray-600">Custom solutions for large organizations</p>
            <p class="mt-6 flex items-baseline gap-x-1">
              <span class="text-4xl font-bold tracking-tight text-gray-900">$199</span>
              <span class="text-sm font-semibold leading-6 text-gray-600">/month</span>
            </p>
            <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600">
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                    clip-rule="evenodd" />
                </svg>
                Unlimited items
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                    clip-rule="evenodd" />
                </svg>
                Unlimited team members
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                    clip-rule="evenodd" />
                </svg>
                Custom analytics
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                    clip-rule="evenodd" />
                </svg>
                24/7 Dedicated support
              </li>
            </ul>
            <a href="{{ route('subscription.checkout', ['plan' => 'enterprise']) }}"
              class="mt-8 block rounded-md bg-indigo-600 px-3.5 py-2 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
              Contact Sales
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-layouts.admin>