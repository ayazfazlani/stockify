<!--
    POSforShops Landing Page — Professional Redesign
    Inspired by BoxHero's clean, modern SaaS aesthetic

    Design Language:
    - Clean white backgrounds with subtle slate-50 sections
    - Single indigo accent (#4F46E5) replacing multiple competing colors
    - Large, confident typography with tight letter-spacing on headlines
    - Generous whitespace — BoxHero breathes, so does this
    - Floating product mockup surrounded by contextual icons (hero signature)
    - Soft shadows (shadow-sm, shadow-md) — no heavy drop shadows
    - Rounded-2xl for cards, rounded-xl for buttons
    - Inline SVG icons replacing Font Awesome for crispness
    - Subtle animations — fade-up, gentle float, no aggressive motion
-->

<div>
  @push('seo')
    <script type="application/ld+json">
                                      {
                                        "@context": "https://schema.org",
                                        "@type": "SoftwareApplication",
                                        "name": "POS for Shops",
                                        "applicationCategory": "BusinessApplication",
                                        "operatingSystem": "All",
                                        "offers": {
                                          "@type": "Offer",
                                          "price": "0",
                                          "priceCurrency": "USD"
                                        },
                                        "description": "AI-powered inventory management solution that helps businesses streamline operations, reduce costs, and boost efficiency.",
                                        "url": "{{ url('/') }}"
                                      }
                                      </script>
  @endpush

  <!-- ========================================== -->
  <!-- HERO SECTION                               -->
  <!-- ========================================== -->
  <section class="relative pt-24 md:pt-32 pb-16 md:pb-20 bg-white overflow-hidden">
    <!-- Decorative blurred blobs -->
    <div
      class="absolute top-20 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-indigo-100 rounded-full blur-3xl opacity-30 pointer-events-none">
    </div>
    <div
      class="absolute bottom-10 right-10 w-[400px] h-[400px] bg-blue-50 rounded-full blur-3xl opacity-40 pointer-events-none">
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <div class="text-center max-w-3xl mx-auto" data-aos="fade-up" data-aos-duration="700">

        <!-- Badge -->
        <div
          class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-700 text-sm font-medium px-4 py-1.5 rounded-full mb-6">
          <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
          New: AI-Powered Inventory Intelligence
        </div>

        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-[1.1] tracking-tighter text-slate-900 mb-6">
          {{ __('app.landing.hero.title') }}<br>
          <span class="text-indigo-600">{{ __('app.landing.hero.subtitle') }}</span>
        </h1>

        <p class="text-lg md:text-xl text-slate-500 leading-relaxed mb-10 max-w-xl mx-auto">
          {{ __('app.landing.hero.description') }}
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
          <a href="{{ route('tenant.register.post') }}"
            class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold text-sm shadow-lg shadow-indigo-200/50 hover:bg-indigo-700 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
            {{ __('app.landing.hero.start_trial') }}
          </a>

          <a href="{{ route('marketplace.index') }}"
            class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-emerald-600 text-white font-bold text-sm shadow-lg shadow-emerald-200/50 hover:bg-emerald-700 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            {{ __('app.landing.hero.browse_marketplace') }}
          </a>

          <a href="#features"
            class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-white text-slate-700 font-bold text-sm border border-slate-200 hover:border-slate-300 hover:bg-slate-50 hover:-translate-y-0.5 transition-all duration-200">
            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
              stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ __('app.landing.hero.watch_demo') }}
          </a>
        </div>

        <p class="text-sm text-slate-400">
          @if(($plans[0]->trial_days ?? 0) > 0)
            {{ __('app.landing.hero.free_trial', ['days' => $plans[0]->trial_days]) }} •
          @endif
          {{ __('app.landing.hero.no_credit_card') }} • {{ __('app.landing.hero.cancel_anytime') }}
        </p>
      </div>
    </div>
  </section>

  <!-- ========================================== -->
  <!-- PRODUCT SHOWCASE — STACKED ON MOBILE       -->
  <!-- ========================================== -->
  <section class="py-16 md:py-24 bg-gray-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Header -->
      <div class="text-center mb-10 md:mb-16">
        <h2 class="text-3xl md:text-5xl font-bold text-gray-900 tracking-tight">
          {{ __('app.landing.showcase.title') }}
        </h2>
        <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
          {{ __('app.landing.showcase.subtitle') }}
        </p>
      </div>

      <!-- MOBILE VIEW: Stacked vertically -->
      <div class="md:hidden space-y-6">

        <!-- Desktop Screenshot -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
          <div class="bg-gray-100 border-b border-gray-200 px-3 py-2 flex items-center gap-2">
            <div class="flex gap-1.5">
              <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
              <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
              <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
            </div>
            <div class="flex-1 mx-2">
              <div
                class="bg-white rounded-md px-2 py-1 text-[10px] text-gray-400 text-center border border-gray-200 truncate">
                posforshops.com
              </div>
            </div>
          </div>
          <img src="{{ asset('assets/posforshops.com_jmart_adjust (1).png') }}" alt="J Mart POS - Desktop View"
            class="w-full h-auto">
        </div>

        <!-- Mobile Screenshot — Centered, not overlapping -->
        <div class="flex justify-center">
          <div class="relative w-48">
            <!-- Phone Frame -->
            <div class="bg-gray-900 rounded-[2rem] p-2 shadow-2xl border-4 border-gray-800">
              <!-- Notch -->
              <div class="absolute top-0 left-1/2 -translate-x-1/2 w-14 h-4 bg-gray-900 rounded-b-xl z-10"></div>
              <div class="bg-white rounded-[1.5rem] overflow-hidden">
                <img src="{{ asset('assets/posforshops.com_jmart_adjust(Samsung Galaxy S20 Ultra).png') }}"
                  alt="J Mart POS - Mobile View" class="w-full h-auto rounded-[1.5rem]">
              </div>
            </div>
            <!-- Label below instead of floating badge -->
            <div class="text-center m-3">
              <span
                class="inline-flex items-center bg-indigo-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-md">
                Mobile Ready
              </span>
            </div>
          </div>
        </div>

      </div>

      <!-- DESKTOP VIEW: Overlapping layout (hidden on mobile) -->
      <div class="hidden md:block relative w-full max-w-6xl mx-auto">

        <!-- Desktop Screenshot -->
        <div class="relative z-10 w-full">
          <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
            <!-- Browser Chrome -->
            <div class="bg-gray-100 border-b border-gray-200 px-4 py-3 flex items-center gap-2">
              <div class="flex gap-1.5">
                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                <div class="w-3 h-3 rounded-full bg-green-400"></div>
              </div>
              <div class="flex-1 mx-4">
                <div
                  class="bg-white rounded-md px-3 py-1 text-xs text-gray-400 text-center border border-gray-200 truncate">
                  posforshops.com/jmart/adjust
                </div>
              </div>
            </div>
            <img src="{{ asset('assets/posforshops.com_jmart_adjust (1).png') }}" alt="J Mart POS - Desktop View"
              class="w-full h-auto">
          </div>
        </div>

        <!-- Mobile Screenshot — Floating Overlapping on Desktop -->
        <div class="absolute -bottom-12 right-12 z-20 w-56">
          <div class="relative">
            <!-- Phone Frame -->
            <div class="bg-gray-900 rounded-[2rem] p-2 shadow-2xl border-4 border-gray-800">
              <!-- Notch -->
              <div class="absolute top-0 left-1/2 -translate-x-1/2 w-16 h-5 bg-gray-900 rounded-b-xl z-10"></div>
              <div class="bg-white rounded-[1.5rem] overflow-hidden">
                <img src="{{ asset('assets/posforshops.com_jmart_adjust(Samsung Galaxy S20 Ultra).png') }}"
                  alt="J Mart POS - Mobile View" class="w-full h-auto rounded-[1.5rem]">
              </div>
            </div>

            <!-- Floating Badge -->
            <div
              class="absolute -top-3 -left-3 bg-indigo-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg block sm:hidden">
              Mobile Ready
            </div>
          </div>
        </div>

      </div>

      <!-- Trust Bar -->
      <div
        class="flex flex-wrap justify-center gap-x-6 md:gap-x-8 gap-y-2 md:gap-y-3 mt-12 md:mt-24 text-xs md:text-sm text-gray-500">
        <div class="flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
          {{ __('app.landing.showcase.real_time') }}
        </div>
        <div class="flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
          {{ __('app.landing.showcase.easy_adjustment') }}
        </div>
        <div class="flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
          {{ __('app.landing.showcase.mobile_optimized') }}
        </div>
      </div>

    </div>
  </section>

  <!-- ========================================== -->
  <!-- TRUSTED BY SECTION                         -->
  <!-- ========================================== -->
  <section class="py-12 bg-slate-50 border-y border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <p class="text-center text-sm text-slate-400 font-medium mb-8 tracking-wide">Trusted by over <span
          class="text-slate-600 font-semibold">2,500 businesses</span> around the world</p>
      <div
        class="flex flex-wrap justify-center items-center gap-x-10 gap-y-6 md:gap-x-16 grayscale opacity-50 contrast-125">
        <span class="text-xl font-black text-slate-900 tracking-tighter">FINTECH</span>
        <span class="text-xl font-black text-slate-900 tracking-tighter">RETX</span>
        <span class="text-xl font-black text-slate-900 tracking-tighter">SUPPLY.IO</span>
        <span class="text-xl font-black text-slate-900 tracking-tighter">EQUITY</span>
      </div>
    </div>
  </section>

  <!-- ========================================== -->
  <!-- FEATURES SECTION                           -->
  <!-- ========================================== -->
  <section id="features" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16 max-w-2xl mx-auto" data-aos="fade-up">
        <h2 class="text-3xl md:text-[2.5rem] font-bold tracking-tight text-slate-900 mb-4 leading-tight">
          {{ __('app.landing.features.title') }}
        </h2>
        <p class="text-lg text-slate-500 leading-relaxed">
          {{ __('app.landing.features.subtitle') }}
        </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Feature 1 -->
        <div
          class="group bg-white rounded-2xl p-7 border border-slate-100 hover:border-indigo-100 hover:shadow-lg hover:shadow-indigo-50/50 transition-all duration-300"
          data-aos="fade-up" data-aos-delay="100">
          <div
            class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center mb-5 group-hover:bg-indigo-100 transition-colors">
            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
              stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ __('app.landing.features.real_time.title') }}</h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-4">{{ __('app.landing.features.real_time.desc') }}</p>
          <a href="#" class="inline-flex items-center text-indigo-600 text-sm font-medium group/link">
            {{ __('app.landing.features.learn_more') }}
            <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </a>
        </div>

        <!-- Feature 2 -->
        <div
          class="group bg-white rounded-2xl p-7 border border-slate-100 hover:border-indigo-100 hover:shadow-lg hover:shadow-indigo-50/50 transition-all duration-300"
          data-aos="fade-up" data-aos-delay="150">
          <div
            class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center mb-5 group-hover:bg-blue-100 transition-colors">
            <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ __('app.landing.features.ai_insights.title') }}</h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-4">{{ __('app.landing.features.ai_insights.desc') }}
          </p>
          <a href="#" class="inline-flex items-center text-indigo-600 text-sm font-medium group/link">
            {{ __('app.landing.features.learn_more') }}
            <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </a>
        </div>

        <!-- Feature 3 -->
        <div
          class="group bg-white rounded-2xl p-7 border border-slate-100 hover:border-indigo-100 hover:shadow-lg hover:shadow-indigo-50/50 transition-all duration-300"
          data-aos="fade-up" data-aos-delay="200">
          <div
            class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center mb-5 group-hover:bg-violet-100 transition-colors">
            <svg class="w-6 h-6 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
              stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ __('app.landing.features.auto_reorder.title') }}</h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-4">{{ __('app.landing.features.auto_reorder.desc') }}
          </p>
          <a href="#" class="inline-flex items-center text-indigo-600 text-sm font-medium group/link">
            {{ __('app.landing.features.learn_more') }}
            <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </a>
        </div>

        <!-- Feature 4 -->
        <div
          class="group bg-white rounded-2xl p-7 border border-slate-100 hover:border-indigo-100 hover:shadow-lg hover:shadow-indigo-50/50 transition-all duration-300"
          data-aos="fade-up" data-aos-delay="250">
          <div
            class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center mb-5 group-hover:bg-emerald-100 transition-colors">
            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
              stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ __('app.landing.features.analytics.title') }}</h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-4">{{ __('app.landing.features.analytics.desc') }}</p>
          <a href="#" class="inline-flex items-center text-indigo-600 text-sm font-medium group/link">
            {{ __('app.landing.features.learn_more') }}
            <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </a>
        </div>

        <!-- Feature 5 -->
        <div
          class="group bg-white rounded-2xl p-7 border border-slate-100 hover:border-indigo-100 hover:shadow-lg hover:shadow-indigo-50/50 transition-all duration-300"
          data-aos="fade-up" data-aos-delay="300">
          <div
            class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center mb-5 group-hover:bg-amber-100 transition-colors">
            <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
              stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ __('app.landing.features.mobile.title') }}</h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-4">{{ __('app.landing.features.mobile.desc') }}</p>
          <a href="#" class="inline-flex items-center text-indigo-600 text-sm font-medium group/link">
            {{ __('app.landing.features.learn_more') }}
            <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </a>
        </div>

        <!-- Feature 6 -->
        <div
          class="group bg-white rounded-2xl p-7 border border-slate-100 hover:border-indigo-100 hover:shadow-lg hover:shadow-indigo-50/50 transition-all duration-300"
          data-aos="fade-up" data-aos-delay="350">
          <div
            class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center mb-5 group-hover:bg-rose-100 transition-colors">
            <svg class="w-6 h-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ __('app.landing.features.integrations.title') }}</h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-4">{{ __('app.landing.features.integrations.desc') }}
          </p>
          <a href="#" class="inline-flex items-center text-indigo-600 text-sm font-medium group/link">
            {{ __('app.landing.features.learn_more') }}
            <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================================== -->
  <!-- SOLUTIONS SECTION                          -->
  <!-- ========================================== -->
  <section id="solutions" class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16 max-w-2xl mx-auto" data-aos="fade-up">
        <h2 class="text-3xl md:text-[2.5rem] font-bold tracking-tight text-slate-900 mb-4 leading-tight">
          {{ __('app.landing.solutions.title') }}
        </h2>
        <p class="text-lg text-slate-500 leading-relaxed">
          {{ __('app.landing.solutions.subtitle') }}
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-6">
        <!-- E-commerce -->
        <div
          class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300"
          data-aos="fade-up" data-aos-delay="100">
          <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center mb-6">
            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
              stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ __('app.landing.solutions.ecommerce.title') }}</h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-5">{{ __('app.landing.solutions.ecommerce.desc') }}
          </p>
          <ul class="space-y-3">
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              {{ __('app.landing.solutions.ecommerce.f1') }}
            </li>
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              {{ __('app.landing.solutions.ecommerce.f2') }}
            </li>
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              {{ __('app.landing.solutions.ecommerce.f3') }}
            </li>
          </ul>
        </div>

        <!-- Manufacturing -->
        <div
          class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300"
          data-aos="fade-up" data-aos-delay="200">
          <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center mb-6">
            <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ __('app.landing.solutions.manufacturing.title') }}
          </h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-5">
            {{ __('app.landing.solutions.manufacturing.desc') }}</p>
          <ul class="space-y-3">
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              {{ __('app.landing.solutions.manufacturing.f1') }}
            </li>
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              {{ __('app.landing.solutions.manufacturing.f2') }}
            </li>
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              {{ __('app.landing.solutions.manufacturing.f3') }}
            </li>
          </ul>
        </div>

        <!-- Logistics -->
        <div
          class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300"
          data-aos="fade-up" data-aos-delay="300">
          <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center mb-6">
            <svg class="w-6 h-6 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
              stroke-width="1.5">
              <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ __('app.landing.solutions.logistics.title') }}</h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-5">{{ __('app.landing.solutions.logistics.desc') }}
          </p>
          <ul class="space-y-3">
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              {{ __('app.landing.solutions.logistics.f1') }}
            </li>
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              {{ __('app.landing.solutions.logistics.f2') }}
            </li>
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              {{ __('app.landing.solutions.logistics.f3') }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================================== -->
  <!-- PRICING SECTION                            -->
  <!-- ========================================== -->
  <section id="pricing" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16 max-w-2xl mx-auto" data-aos="fade-up">
        <h2 class="text-3xl md:text-[2.5rem] font-bold tracking-tight text-slate-900 mb-4 leading-tight">
          {{ __('app.landing.pricing.title') }}
        </h2>
        <p class="text-lg text-slate-500 leading-relaxed">
          {{ __('app.landing.pricing.subtitle', ['days' => $plans[0]->trial_days ?? 14]) }}
        </p>

        <!-- Billing Toggle -->
        <div class="inline-flex items-center bg-slate-100 rounded-xl p-1.5 mt-8">
          <button id="billing-monthly"
            class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-white text-slate-900 shadow-sm transition-all">{{ __('app.landing.pricing.monthly') }}</button>
          <button id="billing-annual"
            class="px-5 py-2.5 rounded-lg text-sm font-semibold text-slate-500 hover:text-slate-700 transition-all">{{ __('app.landing.pricing.annual') }}
            <span class="text-emerald-600">{{ __('app.landing.pricing.save_20') }}</span></button>
        </div>
      </div>

      <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto">
        @foreach($plans as $plan)
          <div
            class="relative bg-white rounded-2xl p-8 border flex flex-col {{ $plan->is_featured ? 'border-indigo-200 ring-1 ring-indigo-100 shadow-xl shadow-indigo-50' : 'border-slate-100 shadow-sm hover:shadow-md hover:border-slate-200' }} transition-all duration-300"
            data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
            @if($plan->is_featured)
              <div class="absolute -top-px left-1/2 -translate-x-1/2">
                <span
                  class="inline-flex items-center bg-indigo-600 text-white px-4 py-1.5 rounded-b-lg text-xs font-semibold tracking-wide">
                  {{ __('app.landing.pricing.most_popular') }}
                </span>
              </div>
            @endif

            <div class="text-center mb-8 {{ $plan->is_featured ? 'mt-4' : '' }}">
              <h3 class="text-lg font-semibold text-slate-900 mb-1">{{ $plan->name }}</h3>
              <p class="text-slate-500 text-sm mb-4">{{ $plan->description }}</p>
              <div class="flex items-baseline justify-center">
                <span
                  class="text-5xl font-bold tracking-tight text-slate-900">{{ config('app.currency_symbol') }}{{ number_format($plan->amount / 100, 0) }}</span>
                <span class="text-slate-400 ml-1.5 text-sm">/{{ $plan->interval }}</span>
              </div>
            </div>

            <ul class="space-y-3.5 mb-8 flex-grow">
              @php
                $groupedFeatures = $plan->getFeaturesGrouped();
                $isEnterprise = $plan->slug === 'enterprise-monthly';
              @endphp

              {{-- Quota Features --}}
              @foreach($plan->planFeatures as $pf)
                @php $enum = \App\Enums\PlanFeature::tryFrom($pf->feature); @endphp
                @if($enum && $enum->type() === 'quota')
                  <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-sm text-slate-600">
                      <strong
                        class="text-slate-800">{{ $pf->value == -1 ? __('app.landing.pricing.unlimited') : $pf->value }}</strong>
                      {{ $enum->label() }}
                    </span>
                  </li>
                @endif
              @endforeach

              {{-- Boolean Features --}}
              @php $count = 0; @endphp
              @foreach($plan->planFeatures as $pf)
                @php $enum = \App\Enums\PlanFeature::tryFrom($pf->feature); @endphp
                @if($enum && $enum->type() === 'boolean' && $count < 6)
                  <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-sm text-slate-600">{{ $enum->label() }}</span>
                  </li>
                  @php $count++; @endphp
                @endif
              @endforeach

              @if($isEnterprise)
                <li class="flex items-start gap-3">
                  <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                  <span class="text-sm font-medium text-slate-800">Custom setup & dedicated support</span>
                </li>
              @endif
            </ul>

            <a href="{{ route('tenant.register.post', ['plan' => $plan->slug]) }}"
              class="w-full text-center py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 {{ $plan->is_featured ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:shadow-xl' : 'bg-slate-900 text-white hover:bg-slate-800' }}">
              {{ $plan->trial_days > 0 ? __('app.landing.pricing.trial_btn') : __('app.landing.pricing.get_started') }}
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ========================================== -->
  <!-- TESTIMONIALS SECTION                       -->
  <!-- ========================================== -->
  <section id="testimonials" class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16 max-w-2xl mx-auto" data-aos="fade-up">
        <h2 class="text-3xl md:text-[2.5rem] font-bold tracking-tight text-slate-900 mb-4 leading-tight">
          Loved by Thousands of Teams
        </h2>
        <p class="text-lg text-slate-500 leading-relaxed">
          Join businesses that have transformed their inventory management with POSforShops.
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-6">
        <!-- Testimonial 1 -->
        <div class="bg-white rounded-2xl p-8 border border-slate-100" data-aos="fade-up" data-aos-delay="100">
          <div class="flex items-center gap-1 mb-5">
            @for($i = 0; $i < 5; $i++)
              <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            @endfor
          </div>
          <p class="text-slate-600 leading-relaxed mb-6">"POSforShops has revolutionized how we manage our inventory. The
            AI predictions have reduced our stockouts by 85% and saved us thousands in carrying costs."</p>
          <div class="flex items-center gap-3 pt-5 border-t border-slate-100">
            <div
              class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-semibold text-sm">
              JS</div>
            <div>
              <p class="font-semibold text-slate-900 text-sm">John Smith</p>
              <p class="text-slate-500 text-xs">CEO, TechGadgets Inc.</p>
            </div>
          </div>
        </div>

        <!-- Testimonial 2 -->
        <div class="bg-white rounded-2xl p-8 border border-slate-100" data-aos="fade-up" data-aos-delay="200">
          <div class="flex items-center gap-1 mb-5">
            @for($i = 0; $i < 5; $i++)
              <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            @endfor
          </div>
          <p class="text-slate-600 leading-relaxed mb-6">"The multi-channel synchronization feature is a game-changer
            for our e-commerce business. We can now manage all our sales channels from one platform."</p>
          <div class="flex items-center gap-3 pt-5 border-t border-slate-100">
            <div
              class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-semibold text-sm">
              SJ</div>
            <div>
              <p class="font-semibold text-slate-900 text-sm">Sarah Johnson</p>
              <p class="text-slate-500 text-xs">Operations Manager, FashionRetail Co.</p>
            </div>
          </div>
        </div>

        <!-- Testimonial 3 -->
        <div class="bg-white rounded-2xl p-8 border border-slate-100" data-aos="fade-up" data-aos-delay="300">
          <div class="flex items-center gap-1 mb-5">
            @for($i = 0; $i < 5; $i++)
              <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            @endfor
          </div>
          <p class="text-slate-600 leading-relaxed mb-6">"The predictive analytics have helped us optimize our inventory
            levels across 12 warehouses. We've reduced excess stock by 40% while improving availability."</p>
          <div class="flex items-center gap-3 pt-5 border-t border-slate-100">
            <div
              class="w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center text-violet-700 font-semibold text-sm">
              MR</div>
            <div>
              <p class="font-semibold text-slate-900 text-sm">Michael Rodriguez</p>
              <p class="text-slate-500 text-xs">Supply Chain Director, AutoParts Ltd.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================================== -->
  <!-- STATS SECTION                              -->
  <!-- ========================================== -->
  <section class="py-20 bg-indigo-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        <div data-aos="fade-up" data-aos-delay="100">
          <div class="text-4xl md:text-5xl font-bold text-white mb-1">2,500+</div>
          <p class="text-indigo-200 text-sm">Businesses Trust Us</p>
        </div>
        <div data-aos="fade-up" data-aos-delay="200">
          <div class="text-4xl md:text-5xl font-bold text-white mb-1">98%</div>
          <p class="text-indigo-200 text-sm">Customer Satisfaction</p>
        </div>
        <div data-aos="fade-up" data-aos-delay="300">
          <div class="text-4xl md:text-5xl font-bold text-white mb-1">$4.2M</div>
          <p class="text-indigo-200 text-sm">Saved Annually</p>
        </div>
        <div data-aos="fade-up" data-aos-delay="400">
          <div class="text-4xl md:text-5xl font-bold text-white mb-1">24/7</div>
          <p class="text-indigo-200 text-sm">Support Available</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================================== -->
  <!-- FAQ SECTION                                -->
  <!-- ========================================== -->
  <section id="faq" class="py-24 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="text-3xl md:text-[2.5rem] font-bold tracking-tight text-slate-900 mb-4 leading-tight">
          Frequently Asked Questions
        </h2>
        <p class="text-lg text-slate-500">Everything you need to know about POSforShops.</p>
      </div>

      <div class="space-y-4" x-data="{ open: 0 }">
        <!-- FAQ 1 -->
        <div class="border border-slate-200 rounded-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="100">
          <button @click="open === 1 ? open = 0 : open = 1"
            class="flex w-full items-center justify-between px-6 py-5 text-left hover:bg-slate-50 transition-colors">
            <span class="font-semibold text-slate-900 text-[15px]">How long does it take to set up POSforShops?</span>
            <svg x-bind:class="open === 1 ? 'rotate-180' : ''"
              class="w-5 h-5 text-slate-400 transition-transform duration-200 shrink-0 ml-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div x-show="open === 1" x-collapse class="px-6 pb-5">
            <p class="text-slate-500 leading-relaxed">Most businesses can get started with POSforShops in under 30 minutes.
              Our intuitive setup wizard guides you through the process, and if you need help, our team is available
              24/7 to assist with implementation.</p>
          </div>
        </div>

        <!-- FAQ 2 -->
        <div class="border border-slate-200 rounded-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="150">
          <button @click="open === 2 ? open = 0 : open = 2"
            class="flex w-full items-center justify-between px-6 py-5 text-left hover:bg-slate-50 transition-colors">
            <span class="font-semibold text-slate-900 text-[15px]">Can I integrate POSforShops with my existing
              systems?</span>
            <svg x-bind:class="open === 2 ? 'rotate-180' : ''"
              class="w-5 h-5 text-slate-400 transition-transform duration-200 shrink-0 ml-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div x-show="open === 2" x-collapse class="px-6 pb-5">
            <p class="text-slate-500 leading-relaxed">Yes! POSforShops offers pre-built integrations with popular
              e-commerce platforms, accounting software, shipping carriers, and ERP systems. We also provide a robust
              API for custom integrations.</p>
          </div>
        </div>

        <!-- FAQ 3 -->
        <div class="border border-slate-200 rounded-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="200">
          <button @click="open === 3 ? open = 0 : open = 3"
            class="flex w-full items-center justify-between px-6 py-5 text-left hover:bg-slate-50 transition-colors">
            <span class="font-semibold text-slate-900 text-[15px]">Is my data secure with POSforShops?</span>
            <svg x-bind:class="open === 3 ? 'rotate-180' : ''"
              class="w-5 h-5 text-slate-400 transition-transform duration-200 shrink-0 ml-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div x-show="open === 3" x-collapse class="px-6 pb-5">
            <p class="text-slate-500 leading-relaxed">Absolutely. We use enterprise-grade security measures including
              encryption at rest and in transit, regular security audits, and compliance with industry standards. Your
              data is backed up daily and stored in secure data centers.</p>
          </div>
        </div>

        <!-- FAQ 4 -->
        <div class="border border-slate-200 rounded-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="250">
          <button @click="open === 4 ? open = 0 : open = 4"
            class="flex w-full items-center justify-between px-6 py-5 text-left hover:bg-slate-50 transition-colors">
            <span class="font-semibold text-slate-900 text-[15px]">What happens after my free trial ends?</span>
            <svg x-bind:class="open === 4 ? 'rotate-180' : ''"
              class="w-5 h-5 text-slate-400 transition-transform duration-200 shrink-0 ml-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div x-show="open === 4" x-collapse class="px-6 pb-5">
            <p class="text-slate-500 leading-relaxed">After your free trial, you can choose any of our paid plans to
              continue using POSforShops. If you decide not to continue, your data will be available for export for 30 days
              before being permanently deleted.</p>
          </div>
        </div>

        <!-- FAQ 5 -->
        <div class="border border-slate-200 rounded-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="300">
          <button @click="open === 5 ? open = 0 : open = 5"
            class="flex w-full items-center justify-between px-6 py-5 text-left hover:bg-slate-50 transition-colors">
            <span class="font-semibold text-slate-900 text-[15px]">Do you offer custom enterprise solutions?</span>
            <svg x-bind:class="open === 5 ? 'rotate-180' : ''"
              class="w-5 h-5 text-slate-400 transition-transform duration-200 shrink-0 ml-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div x-show="open === 5" x-collapse class="px-6 pb-5">
            <p class="text-slate-500 leading-relaxed">Yes, we offer customized enterprise solutions for large
              organizations with complex inventory needs. Contact our sales team to discuss your specific requirements
              and get a tailored proposal.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================================== -->
  <!-- CTA SECTION                                -->
  <!-- ========================================== -->
  <section class="py-24 bg-slate-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-3xl md:text-[2.5rem] font-bold tracking-tight text-white mb-5 leading-tight" data-aos="fade-up">
        Ready to Transform Your<br>Inventory Management?
      </h2>
      <p class="text-lg text-slate-400 mb-10 max-w-xl mx-auto" data-aos="fade-up" data-aos-delay="100">
        Join thousands of businesses using POSforShops to optimize their inventory, reduce costs, and boost efficiency.
      </p>
      <div class="flex flex-col sm:flex-row gap-3 justify-center mb-8" data-aos="fade-up" data-aos-delay="200">
        <a href="#pricing"
          class="inline-flex items-center justify-center px-7 py-3.5 rounded-xl bg-indigo-600 text-white font-semibold text-[15px] shadow-lg shadow-indigo-900/50 hover:bg-indigo-500 hover:-translate-y-0.5 transition-all duration-200">
          {{ __('app.landing.nav.start_trial') }}
        </a>
        <a href="#"
          class="inline-flex items-center justify-center px-7 py-3.5 rounded-xl bg-slate-800 text-white font-semibold text-[15px] border border-slate-700 hover:bg-slate-700 hover:-translate-y-0.5 transition-all duration-200">
          Schedule a Demo
        </a>
      </div>
      <p class="text-sm text-slate-500" data-aos="fade-up" data-aos-delay="300">No credit card required &middot;
        {{ $plans[0]->trial_days ?? 14 }}-day free trial &middot; Cancel anytime
      </p>
    </div>
  </section>

</div>

<!--
    ============================================
    CUSTOM CSS (add to your stylesheet)
    ============================================

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }
    .animate-float {
      animation: float 6s ease-in-out infinite;
    }

    Optional: Smooth scrollbar for webkit browsers
    .scrollbar-thin::-webkit-scrollbar { width: 4px; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
-->