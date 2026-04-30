<div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 -left-1/4 w-1/2 h-full bg-indigo-50 rounded-full blur-[120px] opacity-50"></div>
    <div class="absolute bottom-0 -right-1/4 w-1/2 h-full bg-emerald-50 rounded-full blur-[120px] opacity-50"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-3xl shadow-xl mb-6 text-indigo-600">
                <i class="fas fa-user-plus text-2xl"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Create your account</h2>
            <p class="mt-2 text-sm text-slate-500 font-medium">
                Join our marketplace and start shopping. 
                <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">Already have an account?</a>
            </p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <div class="bg-white py-10 px-6 shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-slate-100 sm:rounded-[2.5rem] sm:px-12 backdrop-blur-sm bg-white/80">
            <form wire:submit="register" class="space-y-6">
                <!-- Full Name -->
                <div>
                    <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-user text-xs"></i>
                        </div>
                        <input wire:model="name" id="name" type="text" placeholder="John Doe"
                            class="block w-full pl-10 pr-4 py-3 bg-slate-50 border-transparent rounded-2xl text-slate-900 focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-sm">
                    </div>
                    @error('name') <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-envelope text-xs"></i>
                        </div>
                        <input wire:model="email" id="email" type="email" placeholder="john@example.com"
                            class="block w-full pl-10 pr-4 py-3 bg-slate-50 border-transparent rounded-2xl text-slate-900 focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-sm">
                    </div>
                    @error('email') <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-lock text-xs"></i>
                        </div>
                        <input wire:model="password" id="password" type="password" placeholder="••••••••"
                            class="block w-full pl-10 pr-4 py-3 bg-slate-50 border-transparent rounded-2xl text-slate-900 focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-sm">
                    </div>
                    @error('password') <p class="mt-1 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-shield text-xs"></i>
                        </div>
                        <input wire:model="password_confirmation" id="password_confirmation" type="password" placeholder="••••••••"
                            class="block w-full pl-10 pr-4 py-3 bg-slate-50 border-transparent rounded-2xl text-slate-900 focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all text-sm">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                        class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-xl text-sm font-black text-white bg-slate-900 hover:bg-indigo-600 focus:outline-none focus:ring-4 focus:ring-indigo-100 transition-all duration-300">
                        Create Account
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-8 border-t border-slate-50">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-100"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase tracking-widest font-black">
                        <span class="px-4 bg-white text-slate-400">Or continue with</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('super-admin.google.redirect') }}" 
                        class="w-full inline-flex justify-center py-3 px-4 rounded-xl shadow-sm bg-white border border-slate-200 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all">
                        <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Google
                    </a>
                </div>
            </div>
        </div>
        
        <div class="mt-8 text-center text-[10px] text-slate-400 font-bold uppercase tracking-widest">
            Are you a business owner? 
            <a href="{{ route('tenant.register.post') }}" class="text-indigo-600 hover:underline">Register your store here</a>
        </div>
    </div>
</div>
