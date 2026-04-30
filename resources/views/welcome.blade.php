<!--
    Stockify Landing Page — Professional Redesign
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

  <!-- ========================================== -->
  <!-- HERO SECTION                               -->
  <!-- ========================================== -->
  <!-- HERO SECTION (Centered) -->
  <!-- ========================================== -->
  <section class="relative pt-24 md:pt-32 pb-20 md:pb-28 bg-white overflow-hidden">
    <!-- Decorative blurred blobs -->
    <div class="absolute top-20 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-indigo-100 rounded-full blur-3xl opacity-30 pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-[400px] h-[400px] bg-blue-50 rounded-full blur-3xl opacity-40 pointer-events-none"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <div class="text-center max-w-3xl mx-auto" data-aos="fade-up" data-aos-duration="700">
        
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-700 text-sm font-medium px-4 py-1.5 rounded-full mb-6">
          <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
          New: AI-Powered Inventory Intelligence
        </div>

        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-[1.1] tracking-tighter text-slate-900 mb-6">
          Inventory Management<br>
          <span class="text-indigo-600">Made Simple</span>
        </h1>

        <p class="text-lg md:text-xl text-slate-500 leading-relaxed mb-10 max-w-xl mx-auto">
          Ditch the spreadsheets. Track, manage, and optimize your inventory in one beautiful, easy-to-use platform.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
          <a href="{{ route('tenant.register.post') }}"
            class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-indigo-600 text-white font-semibold text-base shadow-xl shadow-indigo-200 hover:bg-indigo-700 hover:shadow-2xl hover:shadow-indigo-300 hover:-translate-y-0.5 transition-all duration-200">
            Start Your Free Trial
          </a>

          <a href="{{ route('marketplace.index') }}"
            class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-emerald-600 text-white font-semibold text-base shadow-xl shadow-emerald-200 hover:bg-emerald-700 hover:shadow-2xl hover:shadow-emerald-300 hover:-translate-y-0.5 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            Browse Marketplace
          </a>
          
          <a href="#features"
            class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-white text-slate-700 font-semibold text-base border border-slate-200 hover:border-slate-300 hover:bg-slate-50 hover:-translate-y-0.5 transition-all duration-200">
            <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Watch 2-min Demo
          </a>
        </div>

        <p class="text-sm text-slate-400">
          Free {{ $plans[0]->trial_days ?? 14 }}-day trial • No credit card required • Cancel anytime
        </p>
      </div>

      <!-- Floating App Mockup - Centered & Larger -->
      <div class="flex justify-center mt-16 md:mt-20" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
        <div class="relative">
          <!-- Floating decorative icons -->
          <div class="absolute -top-10 -left-12 w-16 h-16 bg-white rounded-3xl shadow-xl shadow-slate-200/70 flex items-center justify-center animate-float">
            <svg class="w-8 h-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
          </div>

          <div class="absolute top-12 -right-14 w-14 h-14 bg-white rounded-2xl shadow-xl shadow-slate-200/70 flex items-center justify-center animate-float" style="animation-delay: 1.2s;">
            <svg class="w-7 h-7 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>

          <div class="absolute -bottom-8 left-16 w-20 h-20 bg-white rounded-3xl shadow-2xl shadow-slate-200/70 flex items-center justify-center animate-float" style="animation-delay: 2.5s;">
            <svg class="w-9 h-9 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>

          <!-- Main App Mockup -->
          <div class="bg-white rounded-3xl shadow-2xl shadow-slate-300/60 border border-slate-100 p-6 w-full max-w-lg">
            <!-- App Header -->
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
              <div class="flex items-center gap-3">
                <div class="h-9 w-9 bg-indigo-600 rounded-2xl flex items-center justify-center">
                  <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                  </svg>
                </div>
                <div>
                  <span class="font-bold text-slate-900 text-lg tracking-tight">Stockify</span>
                </div>
              </div>
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Live
              </span>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4 mb-6">
              <div class="bg-slate-50 rounded-2xl p-5">
                <p class="text-xs text-slate-500">Total Products</p>
                <p class="text-3xl font-bold text-slate-900 mt-1">1,247</p>
                <div class="flex items-center gap-1 text-emerald-600 text-sm mt-2">
                  <span>↑ 12%</span>
                </div>
              </div>
              <div class="bg-slate-50 rounded-2xl p-5">
                <p class="text-xs text-slate-500">Low Stock Items</p>
                <p class="text-3xl font-bold text-amber-600 mt-1">23</p>
                <div class="flex items-center gap-1 text-rose-600 text-sm mt-2">
                  <span>↓ 5</span>
                </div>
              </div>
            </div>

            <!-- Inventory Health -->
            <div class="mb-6">
              <div class="flex justify-between text-xs mb-2">
                <span class="font-medium text-slate-600">Inventory Health</span>
                <span class="font-bold text-indigo-600">85%</span>
              </div>
              <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-full" style="width: 85%"></div>
              </div>
            </div>

            <!-- Bottom Row -->
            <div class="grid grid-cols-3 text-center pt-6 border-t border-slate-100">
              <div>
                <p class="text-2xl font-bold text-slate-900">12</p>
                <p class="text-[10px] text-slate-500 tracking-widest">PENDING</p>
              </div>
              <div>
                <p class="text-2xl font-bold text-slate-900">8</p>
                <p class="text-[10px] text-slate-500 tracking-widest">SHIPPED</p>
              </div>
              <div>
                <p class="text-2xl font-bold text-slate-900">$4.2k</p>
                <p class="text-[10px] text-slate-500 tracking-widest">REVENUE</p>
              </div>
            </div>
          </div>
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
      <div class="flex flex-wrap justify-center items-center gap-x-10 gap-y-6 md:gap-x-16 opacity-40">
        <!-- Inline SVG wordmarks for a cleaner look -->
        <span
          class="text-xl font-bold text-slate-700 tracking-tight hover:opacity-70 transition-opacity">TechCorp</span>
        <span
          class="text-xl font-bold text-slate-700 tracking-tight hover:opacity-70 transition-opacity">GlobalRetail</span>
        <span
          class="text-xl font-bold text-slate-700 tracking-tight hover:opacity-70 transition-opacity">SwiftSupply</span>
        <span
          class="text-xl font-bold text-slate-700 tracking-tight hover:opacity-70 transition-opacity">InnovateCo</span>
        <span
          class="text-xl font-bold text-slate-700 tracking-tight hover:opacity-70 transition-opacity">PrimeGoods</span>
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
          Features at a glance
        </h2>
        <p class="text-lg text-slate-500 leading-relaxed">
          Track inventory, use analytics, and manage multiple locations with tools built to be simple from the start.
          Here's everything you need to stay organized.
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
          <h3 class="text-lg font-semibold text-slate-900 mb-2">Real-Time Tracking</h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-4">Monitor inventory levels, movements, and status in
            real-time across all your locations with clean visualizations.</p>
          <a href="#" class="inline-flex items-center text-indigo-600 text-sm font-medium group/link">
            Learn more
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
          <h3 class="text-lg font-semibold text-slate-900 mb-2">AI-Powered Insights</h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-4">Get smart recommendations for reordering, pricing,
            and inventory optimization based on predictive analytics.</p>
          <a href="#" class="inline-flex items-center text-indigo-600 text-sm font-medium group/link">
            Learn more
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
          <h3 class="text-lg font-semibold text-slate-900 mb-2">Automated Reordering</h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-4">Set up automatic purchase orders when stock reaches
            predetermined levels, saving time and preventing stockouts.</p>
          <a href="#" class="inline-flex items-center text-indigo-600 text-sm font-medium group/link">
            Learn more
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
          <h3 class="text-lg font-semibold text-slate-900 mb-2">Advanced Analytics</h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-4">Deep dive into your inventory data with
            customizable reports, dashboards, and forecasting tools.</p>
          <a href="#" class="inline-flex items-center text-indigo-600 text-sm font-medium group/link">
            Learn more
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
          <h3 class="text-lg font-semibold text-slate-900 mb-2">Mobile Management</h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-4">Manage your inventory on the go with our intuitive
            mobile app for iOS and Android with offline capabilities.</p>
          <a href="#" class="inline-flex items-center text-indigo-600 text-sm font-medium group/link">
            Learn more
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
          <h3 class="text-lg font-semibold text-slate-900 mb-2">Seamless Integrations</h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-4">Connect with your favorite e-commerce, accounting,
            and shipping platforms with our extensive API.</p>
          <a href="#" class="inline-flex items-center text-indigo-600 text-sm font-medium group/link">
            Learn more
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
          Tailored Solutions for Your Business
        </h2>
        <p class="text-lg text-slate-500 leading-relaxed">
          Stockify adapts to your industry-specific needs with specialized features and workflows.
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
          <h3 class="text-lg font-semibold text-slate-900 mb-2">E-commerce & Retail</h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-5">Manage multi-channel inventory, sync with online
            stores, and automate order fulfillment.</p>
          <ul class="space-y-3">
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              Multi-channel sync
            </li>
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              Automated order processing
            </li>
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              Returns management
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
          <h3 class="text-lg font-semibold text-slate-900 mb-2">Manufacturing</h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-5">Track raw materials, work-in-progress, and finished
            goods across your production lifecycle.</p>
          <ul class="space-y-3">
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              Bill of materials tracking
            </li>
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              Production planning
            </li>
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              Quality control integration
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
          <h3 class="text-lg font-semibold text-slate-900 mb-2">Logistics & Warehousing</h3>
          <p class="text-slate-500 text-[15px] leading-relaxed mb-5">Optimize storage, picking, packing, and shipping
            operations with intelligent warehouse management.</p>
          <ul class="space-y-3">
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              Warehouse layout optimization
            </li>
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              Barcode scanning
            </li>
            <li class="flex items-center text-sm text-slate-600">
              <svg class="w-4 h-4 text-emerald-500 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              Shipping carrier integration
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
          Simple, Transparent Pricing
        </h2>
        <p class="text-lg text-slate-500 leading-relaxed">
          Choose the plan that works best for your business. All plans include a {{ $plans[0]->trial_days ?? 14 }}-day
          free trial.
        </p>

        <!-- Billing Toggle -->
        <div class="inline-flex items-center bg-slate-100 rounded-xl p-1.5 mt-8">
          <button id="billing-monthly"
            class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-white text-slate-900 shadow-sm transition-all">Monthly</button>
          <button id="billing-annual"
            class="px-5 py-2.5 rounded-lg text-sm font-semibold text-slate-500 hover:text-slate-700 transition-all">Annual
            <span class="text-emerald-600">Save 20%</span></button>
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
                  MOST POPULAR
                </span>
              </div>
            @endif

            <div class="text-center mb-8 {{ $plan->is_featured ? 'mt-4' : '' }}">
              <h3 class="text-lg font-semibold text-slate-900 mb-1">{{ $plan->name }}</h3>
              <p class="text-slate-500 text-sm mb-4">{{ $plan->description }}</p>
              <div class="flex items-baseline justify-center">
                <span
                  class="text-5xl font-bold tracking-tight text-slate-900">${{ number_format($plan->amount / 100, 0) }}</span>
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
                      <strong class="text-slate-800">{{ $pf->value == -1 ? 'Unlimited' : $pf->value }}</strong>
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
              {{ $plan->trial_days > 0 ? 'Start Free Trial' : 'Get Started' }}
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
          Join businesses that have transformed their inventory management with Stockify.
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
          <p class="text-slate-600 leading-relaxed mb-6">"Stockify has revolutionized how we manage our inventory. The
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
        <p class="text-lg text-slate-500">Everything you need to know about Stockify.</p>
      </div>

      <div class="space-y-4" x-data="{ open: 0 }">
        <!-- FAQ 1 -->
        <div class="border border-slate-200 rounded-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="100">
          <button @click="open === 1 ? open = 0 : open = 1"
            class="flex w-full items-center justify-between px-6 py-5 text-left hover:bg-slate-50 transition-colors">
            <span class="font-semibold text-slate-900 text-[15px]">How long does it take to set up Stockify?</span>
            <svg x-bind:class="open === 1 ? 'rotate-180' : ''"
              class="w-5 h-5 text-slate-400 transition-transform duration-200 shrink-0 ml-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div x-show="open === 1" x-collapse class="px-6 pb-5">
            <p class="text-slate-500 leading-relaxed">Most businesses can get started with Stockify in under 30 minutes.
              Our intuitive setup wizard guides you through the process, and if you need help, our team is available
              24/7 to assist with implementation.</p>
          </div>
        </div>

        <!-- FAQ 2 -->
        <div class="border border-slate-200 rounded-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="150">
          <button @click="open === 2 ? open = 0 : open = 2"
            class="flex w-full items-center justify-between px-6 py-5 text-left hover:bg-slate-50 transition-colors">
            <span class="font-semibold text-slate-900 text-[15px]">Can I integrate Stockify with my existing
              systems?</span>
            <svg x-bind:class="open === 2 ? 'rotate-180' : ''"
              class="w-5 h-5 text-slate-400 transition-transform duration-200 shrink-0 ml-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div x-show="open === 2" x-collapse class="px-6 pb-5">
            <p class="text-slate-500 leading-relaxed">Yes! Stockify offers pre-built integrations with popular
              e-commerce platforms, accounting software, shipping carriers, and ERP systems. We also provide a robust
              API for custom integrations.</p>
          </div>
        </div>

        <!-- FAQ 3 -->
        <div class="border border-slate-200 rounded-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="200">
          <button @click="open === 3 ? open = 0 : open = 3"
            class="flex w-full items-center justify-between px-6 py-5 text-left hover:bg-slate-50 transition-colors">
            <span class="font-semibold text-slate-900 text-[15px]">Is my data secure with Stockify?</span>
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
              continue using Stockify. If you decide not to continue, your data will be available for export for 30 days
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
        Join thousands of businesses using Stockify to optimize their inventory, reduce costs, and boost efficiency.
      </p>
      <div class="flex flex-col sm:flex-row gap-3 justify-center mb-8" data-aos="fade-up" data-aos-delay="200">
        <a href="#pricing"
          class="inline-flex items-center justify-center px-7 py-3.5 rounded-xl bg-indigo-600 text-white font-semibold text-[15px] shadow-lg shadow-indigo-900/50 hover:bg-indigo-500 hover:-translate-y-0.5 transition-all duration-200">
          Start Your Free Trial
        </a>
        <a href="#"
          class="inline-flex items-center justify-center px-7 py-3.5 rounded-xl bg-slate-800 text-white font-semibold text-[15px] border border-slate-700 hover:bg-slate-700 hover:-translate-y-0.5 transition-all duration-200">
          Schedule a Demo
        </a>
      </div>
      <p class="text-sm text-slate-500" data-aos="fade-up" data-aos-delay="300">No credit card required &middot;
        {{ $plans[0]->trial_days ?? 14 }}-day free trial &middot; Cancel anytime</p>
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