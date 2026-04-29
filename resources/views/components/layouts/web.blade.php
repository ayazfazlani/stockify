<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StockFlow | AI-Powered Inventory Management</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="{{ asset('css/fontawesome/css/all.min.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script>
    tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        accent: {
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                        },
                        dark: {
                            800: '#1f2937',
                            900: '#111827',
                        },
                        teal: {
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                        },
                        indigo: {
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 3s infinite',
                        'fade-in-down': 'fadeInDown 0.5s ease-out',
                        'fade-out-up': 'fadeOutUp 0.5s ease-out',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        fadeInDown: {
                            '0%': { opacity: '0', transform: 'translateY(-10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeOutUp: {
                            '0%': { opacity: '1', transform: 'translateY(0)' },
                            '100%': { opacity: '0', transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
  </script>
  <style>
    :root {
      --gradient-primary: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
      --gradient-accent: linear-gradient(135deg, #10b981 0%, #0d9488 100%);
      --gradient-teal: linear-gradient(135deg, #2dd4bf 0%, #0ea5e9 100%);
      --gradient-dark: linear-gradient(135deg, #1e40af 0%, #7e22ce 100%);
    }

    .gradient-bg {
      background: var(--gradient-primary);
    }

    .gradient-bg-teal {
      background: var(--gradient-teal);
    }

    .gradient-text {
      background: var(--gradient-primary);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .gradient-text-teal {
      background: var(--gradient-teal);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .glass-effect {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .feature-card,
    .pricing-card {
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .hover-lift:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
    }

    .parallax-bg {
      background-attachment: fixed;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
    }

    .text-shadow {
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .nav-link {
      position: relative;
      transition: all 0.3s ease;
    }

    .nav-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: -5px;
      left: 0;
      background: var(--gradient-primary);
      transition: width 0.3s ease;
    }

    .nav-link:hover::after {
      width: 100%;
    }

    .nav-link.active::after {
      width: 100%;
    }

    .faq-item {
      border-bottom: 1px solid #e5e7eb;
    }

    .faq-question {
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .faq-answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease;
    }

    .faq-item.active .faq-answer {
      max-height: 500px;
    }

    .faq-item.active .faq-question {
      color: #0ea5e9;
    }

    .faq-item.active .faq-icon {
      transform: rotate(180deg);
    }
  </style>
</head>


<body class="font-sans antialiased">
   <!-- Navigation -->
   <nav class="fixed w-full z-50 transition-all duration-500" id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 md:h-20">
        <div class="flex items-center">
          <div class="flex-shrink-0 flex items-center">
            <div class="rounded-lg gradient-bg p-2 mr-3 transform hover:rotate-12 transition-transform duration-300">
              <i class="fas fa-boxes text-white text-xl"></i>
            </div>
            <span
              class="font-bold text-2xl bg-gradient-to-r from-primary-600 to-indigo-600 bg-clip-text text-transparent">StockFlow</span>
          </div>
        </div>
        <div class="hidden md:flex items-center space-x-8">
          <a href="#features"
            class="nav-link text-dark-800 font-medium hover:text-primary-600 transition-colors duration-300">Features</a>
          <a href="#solutions"
            class="nav-link text-dark-800 font-medium hover:text-primary-600 transition-colors duration-300">Solutions</a>
          <a href="#pricing"
            class="nav-link text-dark-800 font-medium hover:text-primary-600 transition-colors duration-300">Pricing</a>
          <a href="#testimonials"
            class="nav-link text-dark-800 font-medium hover:text-primary-600 transition-colors duration-300">Testimonials</a>
          <a href="#faq"
            class="nav-link text-dark-800 font-medium hover:text-primary-600 transition-colors duration-300">FAQ</a>
          <a href="{{ route('find-store') }}" class="text-dark-800 font-medium hover:text-primary-600 transition-colors duration-300">Login</a>
          <a href="{{ route('tenant.register.post') }}"
            class="bg-gradient-to-r from-primary-500 to-indigo-500 text-white px-5 py-2.5 rounded-lg font-medium hover:from-primary-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">Start
            Free Trial</a>
        </div>
        <div class="md:hidden flex items-center">
          <button id="mobile-menu-button" class="text-dark-800 hover:text-primary-600 transition-colors duration-300">
            <i class="fas fa-bars text-2xl"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu"
      class="hidden md:hidden absolute w-full bg-white/95 backdrop-blur-md shadow-lg py-4 transition-all duration-500 ease-in-out">
      <div class="px-4 pt-2 pb-3 space-y-1">
        <a href="#features"
          class="block px-3 py-3 text-dark-800 font-medium hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-300">Features</a>
        <a href="#solutions"
          class="block px-3 py-3 text-dark-800 font-medium hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-300">Solutions</a>
        <a href="#pricing"
          class="block px-3 py-3 text-dark-800 font-medium hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-300">Pricing</a>
        <a href="#testimonials"
          class="block px-3 py-3 text-dark-800 font-medium hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-300">Testimonials</a>
        <a href="#faq"
          class="block px-3 py-3 text-dark-800 font-medium hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-300">FAQ</a>
        <a href="{{ route('find-store') }}"
          class="block px-3 py-3 text-dark-800 font-medium hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-300">Login</a>
        <a href="{{ route('tenant.register.post') }}"
          class="block mx-3 mt-4 bg-gradient-to-r from-primary-500 to-indigo-500 text-white px-5 py-3 rounded-lg font-medium text-center shadow-md hover:shadow-lg transition-all duration-300">Start
          Free Trial</a>
      </div>
    </div>
  </nav>


{{ $slot }}
<!-- Footer -->
  <footer class="bg-dark-900 text-white pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
        <div class="lg:col-span-2">
          <div class="flex items-center mb-6">
            <div class="rounded-lg gradient-bg p-2 mr-3">
              <i class="fas fa-boxes text-white text-xl"></i>
            </div>
            <span class="font-bold text-2xl">StockFlow</span>
          </div>
          <p class="text-gray-400 mb-6 max-w-md">AI-powered inventory management solution that helps businesses
            streamline operations, reduce costs, and boost efficiency.</p>
          <div class="flex space-x-4">
            <a href="#"
              class="w-10 h-10 rounded-full bg-dark-800 flex items-center justify-center hover:bg-primary-500 transition-colors duration-300">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#"
              class="w-10 h-10 rounded-full bg-dark-800 flex items-center justify-center hover:bg-primary-500 transition-colors duration-300">
              <i class="fab fa-linkedin-in"></i>
            </a>
            <a href="#"
              class="w-10 h-10 rounded-full bg-dark-800 flex items-center justify-center hover:bg-primary-500 transition-colors duration-300">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#"
              class="w-10 h-10 rounded-full bg-dark-800 flex items-center justify-center hover:bg-primary-500 transition-colors duration-300">
              <i class="fab fa-instagram"></i>
            </a>
          </div>
        </div>
        <div>
          <h3 class="font-semibold text-lg mb-6">Product</h3>
          <ul class="space-y-3">
            <li><a href="#features" class="text-gray-400 hover:text-white transition-colors duration-300">Features</a>
            </li>
            <li><a href="#solutions" class="text-gray-400 hover:text-white transition-colors duration-300">Solutions</a>
            </li>
            <li><a href="#pricing" class="text-gray-400 hover:text-white transition-colors duration-300">Pricing</a>
            </li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">API Docs</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Mobile App</a></li>
          </ul>
        </div>
        <div>
          <h3 class="font-semibold text-lg mb-6">Company</h3>
          <ul class="space-y-3">
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">About Us</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Careers</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Blog</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Press</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Contact</a></li>
          </ul>
        </div>
        <div>
          <h3 class="font-semibold text-lg mb-6">Support</h3>
          <ul class="space-y-3">
            <li><a href="#faq" class="text-gray-400 hover:text-white transition-colors duration-300">Help Center</a>
            </li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Community</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Status</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Privacy Policy</a>
            </li>
            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Terms of Service</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="pt-8 border-t border-gray-800 text-center text-gray-400 text-sm">
        <p>&copy; 2023 StockFlow. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script>
    // Initialize AOS
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                once: true,
                offset: 100
            });

            // Navbar scroll effect
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('bg-white', 'shadow-md');
                    navbar.classList.remove('bg-transparent');
                } else {
                    navbar.classList.remove('bg-white', 'shadow-md');
                    navbar.classList.add('bg-transparent');
                }
            });

            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });

            // FAQ accordion
            const faqItems = document.querySelectorAll('.faq-item');
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                question.addEventListener('click', () => {
                    item.classList.toggle('active');
                });
            });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });

                        // Close mobile menu if open
                        mobileMenu.classList.add('hidden');
                    }
                });
            });
        });
  </script>
</body>
</html>

