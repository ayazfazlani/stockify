<div>
 
  <!-- Hero Section -->
  <section class="pt-28 md:pt-36 pb-20 md:pb-32 gradient-bg text-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <div class="absolute top-10 right-10 w-72 h-72 bg-white opacity-10 rounded-full blur-xl animate-pulse-slow"></div>
      <div class="absolute bottom-10 left-10 w-96 h-96 bg-teal-400 opacity-10 rounded-full blur-xl animate-pulse-slow"
        style="animation-delay: 1s;"></div>

      <div class="flex flex-col lg:flex-row items-center">
        <div class="lg:w-1/2 mb-12 lg:mb-0" data-aos="fade-right" data-aos-duration="800">
          <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6 text-shadow">AI-Powered Inventory
            <span class="gradient-text-teal">Management</span> Solution
          </h1>
          <p class="text-xl md:text-2xl mb-8 opacity-90">Streamline your operations, reduce costs, and boost efficiency
            with our intelligent inventory platform.</p>
          <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
            <a href="{{ route('tenant.register.post') }}"
              class="bg-white text-primary-600 px-8 py-4 rounded-lg font-semibold text-center shadow-lg hover-lift transition-all duration-300 transform hover:-translate-y-1">Start
              Free Trial</a>
            <a href="#features"
              class="glass-effect border border-white border-opacity-30 text-white px-8 py-4 rounded-lg font-semibold text-center hover:bg-white hover:bg-opacity-10 transition-all duration-300 transform hover:-translate-y-1">Watch
              Demo <i class="fas fa-play-circle ml-2"></i></a>
          </div>
          <p class="mt-6 text-primary-100 text-sm"><i class="fas fa-star mr-1"></i> Rated 4.9/5 by 2,500+ businesses</p>
        </div>
        <div class="lg:w-1/2 flex justify-center" data-aos="fade-left" data-aos-duration="800" data-aos-delay="200">
          <div class="relative">
            <div
              class="bg-white rounded-2xl shadow-2xl p-6 text-dark-800 w-full max-w-md transform rotate-3 hover:rotate-0 transition-transform duration-500">
              <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg">Inventory Dashboard</h3>
                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">Live</span>
              </div>
              <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-primary-50 p-4 rounded-lg">
                  <p class="text-sm text-gray-600">Total Products</p>
                  <p class="text-2xl font-bold text-primary-700">1,247</p>
                </div>
                <div class="bg-teal-50 p-4 rounded-lg">
                  <p class="text-sm text-gray-600">Low Stock</p>
                  <p class="text-2xl font-bold text-teal-600">23</p>
                </div>
              </div>
              <div class="mb-6">
                <div class="flex justify-between mb-1">
                  <span class="text-sm font-medium">Inventory Health</span>
                  <span class="text-sm font-bold text-primary-600">85%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                  <div class="bg-gradient-to-r from-green-400 to-green-600 h-2.5 rounded-full" style="width: 85%"></div>
                </div>
              </div>
              <div class="flex justify-between text-center">
                <div>
                  <p class="text-2xl font-bold text-primary-600">12</p>
                  <p class="text-xs text-gray-500">Pending Orders</p>
                </div>
                <div>
                  <p class="text-2xl font-bold text-teal-600">8</p>
                  <p class="text-xs text-gray-500">Shipments Today</p>
                </div>
                <div>
                  <p class="text-2xl font-bold text-indigo-600">$4.2K</p>
                  <p class="text-xs text-gray-500">Daily Revenue</p>
                </div>
              </div>
            </div>
            <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-teal-400 rounded-2xl -z-10 animate-float"></div>
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-primary-500 rounded-2xl -z-10 animate-float"
              style="animation-delay: 2s;"></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Trusted By Section -->
  <section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <p class="text-center text-gray-500 mb-12 text-lg">Trusted by innovative companies worldwide</p>
      <div class="grid grid-cols-2 md:grid-cols-5 gap-8 items-center justify-items-center opacity-60">
        <div class="text-gray-700 text-2xl font-bold transform hover:scale-110 transition-transform duration-300">
          TechCorp</div>
        <div class="text-gray-700 text-2xl font-bold transform hover:scale-110 transition-transform duration-300">
          GlobalRetail</div>
        <div class="text-gray-700 text-2xl font-bold transform hover:scale-110 transition-transform duration-300">
          SwiftSupply</div>
        <div class="text-gray-700 text-2xl font-bold transform hover:scale-110 transition-transform duration-300">
          InnovateCo</div>
        <div class="text-gray-700 text-2xl font-bold transform hover:scale-110 transition-transform duration-300">
          PrimeGoods</div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Powerful Features for Complete <span
            class="gradient-text-teal">Inventory Control</span></h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">Our platform offers everything you need to manage your
          inventory efficiently and make data-driven decisions.</p>
      </div>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div
          class="feature-card bg-gradient-to-br from-white to-gray-50 p-8 rounded-2xl shadow-md hover-lift border border-gray-100"
          data-aos="fade-up" data-aos-delay="100">
          <div
            class="bg-gradient-to-r from-primary-500 to-indigo-500 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 text-white text-2xl">
            <i class="fas fa-search"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">Real-Time Tracking</h3>
          <p class="text-gray-600">Monitor inventory levels, movements, and status in real-time across all your
            locations with beautiful visualizations.</p>
          <a href="#" class="inline-flex items-center text-primary-600 font-medium mt-4 group">
            Learn more <i
              class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-200"></i>
          </a>
        </div>
        <div
          class="feature-card bg-gradient-to-br from-white to-gray-50 p-8 rounded-2xl shadow-md hover-lift border border-gray-100"
          data-aos="fade-up" data-aos-delay="200">
          <div
            class="bg-gradient-to-r from-teal-500 to-teal-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 text-white text-2xl">
            <i class="fas fa-robot"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">AI-Powered Insights</h3>
          <p class="text-gray-600">Get smart recommendations for reordering, pricing, and inventory optimization based
            on predictive analytics.</p>
          <a href="#" class="inline-flex items-center text-primary-600 font-medium mt-4 group">
            Learn more <i
              class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-200"></i>
          </a>
        </div>
        <div
          class="feature-card bg-gradient-to-br from-white to-gray-50 p-8 rounded-2xl shadow-md hover-lift border border-gray-100"
          data-aos="fade-up" data-aos-delay="300">
          <div
            class="bg-gradient-to-r from-purple-500 to-purple-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 text-white text-2xl">
            <i class="fas fa-sync-alt"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">Automated Reordering</h3>
          <p class="text-gray-600">Set up automatic purchase orders when stock reaches predetermined levels, saving time
            and preventing stockouts.</p>
          <a href="#" class="inline-flex items-center text-primary-600 font-medium mt-4 group">
            Learn more <i
              class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-200"></i>
          </a>
        </div>
        <div
          class="feature-card bg-gradient-to-br from-white to-gray-50 p-8 rounded-2xl shadow-md hover-lift border border-gray-100"
          data-aos="fade-up" data-aos-delay="400">
          <div
            class="bg-gradient-to-r from-red-500 to-red-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 text-white text-2xl">
            <i class="fas fa-chart-bar"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">Advanced Analytics</h3>
          <p class="text-gray-600">Deep dive into your inventory data with customizable reports, dashboards, and
            forecasting tools.</p>
          <a href="#" class="inline-flex items-center text-primary-600 font-medium mt-4 group">
            Learn more <i
              class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-200"></i>
          </a>
        </div>
        <div
          class="feature-card bg-gradient-to-br from-white to-gray-50 p-8 rounded-2xl shadow-md hover-lift border border-gray-100"
          data-aos="fade-up" data-aos-delay="500">
          <div
            class="bg-gradient-to-r from-yellow-500 to-yellow-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 text-white text-2xl">
            <i class="fas fa-mobile-alt"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">Mobile Management</h3>
          <p class="text-gray-600">Manage your inventory on the go with our intuitive mobile app for iOS and Android
            with offline capabilities.</p>
          <a href="#" class="inline-flex items-center text-primary-600 font-medium mt-4 group">
            Learn more <i
              class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-200"></i>
          </a>
        </div>
        <div
          class="feature-card bg-gradient-to-br from-white to-gray-50 p-8 rounded-2xl shadow-md hover-lift border border-gray-100"
          data-aos="fade-up" data-aos-delay="600">
          <div
            class="bg-gradient-to-r from-indigo-500 to-indigo-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 text-white text-2xl">
            <i class="fas fa-plug"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">Seamless Integrations</h3>
          <p class="text-gray-600">Connect with your favorite e-commerce, accounting, and shipping platforms with our
            extensive API.</p>
          <a href="#" class="inline-flex items-center text-primary-600 font-medium mt-4 group">
            Learn more <i
              class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-200"></i>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Solutions Section -->
  {{-- add overlay on the background image --}}
  <section id="solutions" class="relative py-20 bg-gradient-to-br from-gray-50 to-white parallax-bg bg-cover bg-center"
    style="background-image: url('assets/images/screenshot.png'); background-attachment: fixed;">

    <!-- Transparent overlay -->
    <div class="absolute inset-0 bg-black/75"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold mb-4 text-white">Tailored Solutions for Your Business</h2>
        <p class="text-xl text-gray-100 max-w-3xl mx-auto">
          StockFlow adapts to your industry-specific needs with specialized features and workflows.
        </p>
      </div>
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-white rounded-2xl p-8 shadow-lg hover-lift transition-all duration-300" data-aos="fade-up"
          data-aos-delay="100">
          <div
            class="bg-primary-100 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 text-primary-600 text-2xl">
            <i class="fas fa-store"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">E-commerce & Retail</h3>
          <p class="text-gray-600 mb-4">Manage multi-channel inventory, sync with online stores, and automate order
            fulfillment.</p>
          <ul class="space-y-2">
            <li class="flex items-center"><i class="fas fa-check text-primary-500 mr-2"></i> Multi-channel sync</li>
            <li class="flex items-center"><i class="fas fa-check text-primary-500 mr-2"></i> Automated order processing
            </li>
            <li class="flex items-center"><i class="fas fa-check text-primary-500 mr-2"></i> Returns management</li>
          </ul>
        </div>
        <div class="bg-white rounded-2xl p-8 shadow-lg hover-lift transition-all duration-300" data-aos="fade-up"
          data-aos-delay="200">
          <div class="bg-teal-100 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 text-teal-600 text-2xl">
            <i class="fas fa-industry"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">Manufacturing</h3>
          <p class="text-gray-600 mb-4">Track raw materials, work-in-progress, and finished goods across your production
            lifecycle.</p>
          <ul class="space-y-2">
            <li class="flex items-center"><i class="fas fa-check text-teal-500 mr-2"></i> Bill of materials tracking
            </li>
            <li class="flex items-center"><i class="fas fa-check text-teal-500 mr-2"></i> Production planning</li>
            <li class="flex items-center"><i class="fas fa-check text-teal-500 mr-2"></i> Quality control integration
            </li>
          </ul>
        </div>
        <div class="bg-white rounded-2xl p-8 shadow-lg hover-lift transition-all duration-300" data-aos="fade-up"
          data-aos-delay="300">
          <div
            class="bg-indigo-100 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 text-indigo-600 text-2xl">
            <i class="fas fa-truck"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">Logistics & Warehousing</h3>
          <p class="text-gray-600 mb-4">Optimize storage, picking, packing, and shipping operations with intelligent
            warehouse management.</p>
          <ul class="space-y-2">
            <li class="flex items-center"><i class="fas fa-check text-indigo-500 mr-2"></i> Warehouse layout
              optimization</li>
            <li class="flex items-center"><i class="fas fa-check text-indigo-500 mr-2"></i> Barcode scanning</li>
            <li class="flex items-center"><i class="fas fa-check text-indigo-500 mr-2"></i> Shipping carrier integration
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- Pricing Section -->
  <section id="pricing" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Simple, Transparent <span
            class="gradient-text-teal">Pricing</span></h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">Choose the plan that works best for your business. All plans
          include a 14-day free trial.</p>
        <div class="inline-flex bg-white rounded-lg p-1 mt-8 shadow-md">
          <button
            class="px-4 py-2 rounded-lg font-medium bg-primary-500 text-white transition-all duration-300">Monthly</button>
          <button
            class="px-4 py-2 rounded-lg font-medium text-gray-600 hover:text-primary-600 transition-all duration-300">Annual
            (Save 20%)</button>
        </div>
      </div>
      <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
        @foreach($plans as $plan)
        <div class="pricing-card bg-white p-8 rounded-2xl shadow-md hover-lift border {{ $plan->is_featured ? 'border-primary-500 ring-2 ring-primary-500/20' : 'border-gray-200' }} flex flex-col"
          data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
          @if($plan->is_featured)
          <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <span class="bg-gradient-to-r from-primary-500 to-indigo-500 text-white px-4 py-1 rounded-full text-sm font-semibold shadow-md border border-white">MOST POPULAR</span>
          </div>
          @endif

          <div class="text-center mb-8">
            <h3 class="text-xl font-bold mb-2">{{ $plan->name }}</h3>
            <div class="flex items-baseline justify-center">
              <span class="text-4xl font-bold">${{ number_format($plan->amount / 100, 0) }}</span>
              <span class="text-gray-500 ml-1">/{{ $plan->interval }}</span>
            </div>
            <p class="text-gray-600 mt-2 text-sm">{{ $plan->description }}</p>
          </div>

          <ul class="space-y-4 mb-8 flex-grow">
            @php
                $groupedFeatures = $plan->getFeaturesGrouped();
                $isEnterprise = $plan->slug === 'enterprise-monthly';
            @endphp

            {{-- Display Quota Features First (Limits) --}}
            @foreach($plan->planFeatures as $pf)
                @php $enum = \App\Enums\PlanFeature::tryFrom($pf->feature); @endphp
                @if($enum && $enum->type() === 'quota')
                <li class="flex items-start">
                  <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                  <span class="text-sm">
                    <strong>{{ $pf->value == -1 ? 'Unlimited' : $pf->value }}</strong> {{ $enum->label() }}
                  </span>
                </li>
                @endif
            @endforeach

            {{-- Display Boolean Features --}}
            @php $count = 0; @endphp
            @foreach($plan->planFeatures as $pf)
                @php $enum = \App\Enums\PlanFeature::tryFrom($pf->feature); @endphp
                @if($enum && $enum->type() === 'boolean' && $count < 6)
                <li class="flex items-start">
                  <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                  <span class="text-sm">{{ $enum->label() }}</span>
                </li>
                @php $count++; @endphp
                @endif
            @endforeach

            @if($isEnterprise)
            <li class="flex items-start">
              <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
              <span class="text-sm font-semibold">Custom Setup & dedicated support</span>
            </li>
            @endif
          </ul>

          <a href="{{ route('tenant.register.post', ['plan' => $plan->slug]) }}"
            class="w-full text-center py-3 rounded-lg font-semibold transition-all duration-200 transform hover:-translate-y-1 {{ $plan->is_featured ? 'gradient-bg text-white shadow-lg shadow-primary-500/30' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
            {{ $plan->trail_days > 0 ? 'Start '.$plan->trial_days.'-Day Free Trial' : 'Get Started' }}
          </a>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Testimonials
          <!-- Testimonials Section -->
  <section id="testimonials" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">What Our <span class="gradient-text-teal">Customers Say</span>
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">Join thousands of businesses that have transformed their
          inventory management with StockFlow.</p>
      </div>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="bg-gray-50 p-8 rounded-2xl shadow-sm hover-lift transition-all duration-300" data-aos="fade-up"
          data-aos-delay="100">
          <div class="flex items-center mb-6">
            <div
              class="w-12 h-12 bg-gradient-to-r from-primary-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
              JS</div>
            <div class="ml-4">
              <h4 class="font-bold">John Smith</h4>
              <p class="text-gray-600 text-sm">CEO, TechGadgets Inc.</p>
            </div>
          </div>
          <div class="flex text-yellow-400 mb-4">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
          </div>
          <p class="text-gray-700 italic">"StockFlow has revolutionized how we manage our inventory. The AI predictions
            have reduced our stockouts by 85% and saved us thousands in carrying costs."</p>
        </div>
        <div class="bg-gray-50 p-8 rounded-2xl shadow-sm hover-lift transition-all duration-300" data-aos="fade-up"
          data-aos-delay="200">
          <div class="flex items-center mb-6">
            <div
              class="w-12 h-12 bg-gradient-to-r from-teal-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
              SR</div>
            <div class="ml-4">
              <h4 class="font-bold">Sarah Johnson</h4>
              <p class="text-gray-600 text-sm">Operations Manager, FashionRetail Co.</p>
            </div>
          </div>
          <div class="flex text-yellow-400 mb-4">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
          </div>
          <p class="text-gray-700 italic">"The multi-channel synchronization feature is a game-changer for our
            e-commerce business. We can now manage all our sales channels from one platform."</p>
        </div>
        <div class="bg-gray-50 p-8 rounded-2xl shadow-sm hover-lift transition-all duration-300" data-aos="fade-up"
          data-aos-delay="300">
          <div class="flex items-center mb-6">
            <div
              class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
              MR</div>
            <div class="ml-4">
              <h4 class="font-bold">Michael Rodriguez</h4>
              <p class="text-gray-600 text-sm">Supply Chain Director, AutoParts Ltd.</p>
            </div>
          </div>
          <div class="flex text-yellow-400 mb-4">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
          </div>
          <p class="text-gray-700 italic">"The predictive analytics have helped us optimize our inventory levels across
            12 warehouses. We've reduced excess stock by 40% while improving availability."</p>
        </div>
      </div>
      <div class="text-center mt-12">
        <div class="inline-flex space-x-2">
          <div class="w-3 h-3 bg-primary-500 rounded-full"></div>
          <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
          <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="py-16 gradient-bg text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        <div data-aos="fade-up" data-aos-delay="100">
          <div class="text-4xl md:text-5xl font-bold mb-2">2,500+</div>
          <p class="text-primary-100">Businesses Trust Us</p>
        </div>
        <div data-aos="fade-up" data-aos-delay="200">
          <div class="text-4xl md:text-5xl font-bold mb-2">98%</div>
          <p class="text-primary-100">Customer Satisfaction</p>
        </div>
        <div data-aos="fade-up" data-aos-delay="300">
          <div class="text-4xl md:text-5xl font-bold mb-2">$4.2M</div>
          <p class="text-primary-100">Saved Annually</p>
        </div>
        <div data-aos="fade-up" data-aos-delay="400">
          <div class="text-4xl md:text-5xl font-bold mb-2">24/7</div>
          <p class="text-primary-100">Support Available</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section id="faq" class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Frequently Asked <span
            class="gradient-text-teal">Questions</span></h2>
        <p class="text-xl text-gray-600">Get answers to common questions about StockFlow.</p>
      </div>
      <div class="space-y-6">
        <div class="faq-item bg-white p-6 rounded-2xl shadow-sm" data-aos="fade-up" data-aos-delay="100">
          <div class="faq-question flex justify-between items-center">
            <h3 class="text-lg font-semibold">How long does it take to set up StockFlow?</h3>
            <span class="faq-icon text-primary-500 transition-transform duration-300">
              <i class="fas fa-chevron-down"></i>
            </span>
          </div>
          <div class="faq-answer mt-4">
            <p class="text-gray-600">Most businesses can get started with StockFlow in under 30 minutes. Our intuitive
              setup wizard guides you through the process, and if you need help, our team is available 24/7 to assist
              with implementation.</p>
          </div>
        </div>
        <div class="faq-item bg-white p-6 rounded-2xl shadow-sm" data-aos="fade-up" data-aos-delay="200">
          <div class="faq-question flex justify-between items-center">
            <h3 class="text-lg font-semibold">Can I integrate StockFlow with my existing systems?</h3>
            <span class="faq-icon text-primary-500 transition-transform duration-300">
              <i class="fas fa-chevron-down"></i>
            </span>
          </div>
          <div class="faq-answer mt-4">
            <p class="text-gray-600">Yes! StockFlow offers pre-built integrations with popular e-commerce platforms,
              accounting software, shipping carriers, and ERP systems. We also provide a robust API for custom
              integrations.</p>
          </div>
        </div>
        <div class="faq-item bg-white p-6 rounded-2xl shadow-sm" data-aos="fade-up" data-aos-delay="300">
          <div class="faq-question flex justify-between items-center">
            <h3 class="text-lg font-semibold">Is my data secure with StockFlow?</h3>
            <span class="faq-icon text-primary-500 transition-transform duration-300">
              <i class="fas fa-chevron-down"></i>
            </span>
          </div>
          <div class="faq-answer mt-4">
            <p class="text-gray-600">Absolutely. We use enterprise-grade security measures including encryption at rest
              and in transit, regular security audits, and compliance with industry standards. Your data is backed up
              daily and stored in secure data centers.</p>
          </div>
        </div>
        <div class="faq-item bg-white p-6 rounded-2xl shadow-sm" data-aos="fade-up" data-aos-delay="400">
          <div class="faq-question flex justify-between items-center">
            <h3 class="text-lg font-semibold">What happens after my free trial ends?</h3>
            <span class="faq-icon text-primary-500 transition-transform duration-300">
              <i class="fas fa-chevron-down"></i>
            </span>
          </div>
          <div class="faq-answer mt-4">
            <p class="text-gray-600">After your 14-day free trial, you can choose any of our paid plans to continue
              using StockFlow. If you decide not to continue, your data will be available for export for 30 days before
              being permanently deleted.</p>
          </div>
        </div>
        <div class="faq-item bg-white p-6 rounded-2xl shadow-sm" data-aos="fade-up" data-aos-delay="500">
          <div class="faq-question flex justify-between items-center">
            <h3 class="text-lg font-semibold">Do you offer custom enterprise solutions?</h3>
            <span class="faq-icon text-primary-500 transition-transform duration-300">
              <i class="fas fa-chevron-down"></i>
            </span>
          </div>
          <div class="faq-answer mt-4">
            <p class="text-gray-600">Yes, we offer customized enterprise solutions for large organizations with complex
              inventory needs. Contact our sales team to discuss your specific requirements and get a tailored proposal.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-20 gradient-bg-teal text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-3xl md:text-4xl font-bold mb-6 text-shadow" data-aos="fade-up">Ready to Transform Your Inventory
        Management?</h2>
      <p class="text-xl mb-8 opacity-90 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">Join thousands of
        businesses using StockFlow to optimize their inventory, reduce costs, and boost efficiency.</p>
      <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4" data-aos="fade-up"
        data-aos-delay="200">
        <a href="#pricing"
          class="bg-white text-teal-600 px-8 py-4 rounded-lg font-semibold text-center shadow-lg hover-lift transition-all duration-300 transform hover:-translate-y-1">Start
          Your Free Trial</a>
        <a href="#"
          class="glass-effect border border-white border-opacity-30 text-white px-8 py-4 rounded-lg font-semibold text-center hover:bg-white hover:bg-opacity-10 transition-all duration-300 transform hover:-translate-y-1">Schedule
          a Demo</a>
      </div>
      <p class="mt-6 text-teal-100 text-sm" data-aos="fade-up" data-aos-delay="300">No credit card required • 14-day
        free trial • Cancel anytime</p>
    </div>
  </section>

  </div>
