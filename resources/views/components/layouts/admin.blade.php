<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SaaS Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  @vite("resources/css/app.css");
  <style>
    :root {
      --background: 0 0% 100%;
      --foreground: 222.2 84% 4.9%;
      --card: 0 0% 100%;
      --card-foreground: 222.2 84% 4.9%;
      --popover: 0 0% 100%;
      --popover-foreground: 222.2 84% 4.9%;
      --primary: 221.2 83.2% 53.3%;
      --primary-foreground: 210 40% 98%;
      --secondary: 210 40% 96%;
      --secondary-foreground: 222.2 84% 4.9%;
      --muted: 210 40% 96%;
      --muted-foreground: 215.4 16.3% 46.9%;
      --accent: 210 40% 96%;
      --accent-foreground: 222.2 84% 4.9%;
      --destructive: 0 84.2% 60.2%;
      --destructive-foreground: 210 40% 98%;
      --border: 214.3 31.8% 91.4%;
      --input: 214.3 31.8% 91.4%;
      --ring: 221.2 83.2% 53.3%;
      --radius: 0.5rem;
      --sidebar-background: 0 0% 98%;
      --sidebar-foreground: 240 5.3% 26.1%;
      --sidebar-primary: 240 5.9% 10%;
      --sidebar-primary-foreground: 0 0% 98%;
      --sidebar-accent: 240 4.8% 95.9%;
      --sidebar-accent-foreground: 240 5.9% 10%;
      --sidebar-border: 220 13% 91%;
      --sidebar-ring: 217.2 91.2% 59.8%;
    }

    .dark {
      --background: 222.2 84% 4.9%;
      --foreground: 210 40% 98%;
      --card: 222.2 84% 4.9%;
      --card-foreground: 210 40% 98%;
      --popover: 222.2 84% 4.9%;
      --popover-foreground: 210 40% 98%;
      --primary: 217.2 91.2% 59.8%;
      --primary-foreground: 222.2 84% 4.9%;
      --secondary: 217.2 32.6% 17.5%;
      --secondary-foreground: 210 40% 98%;
      --muted: 217.2 32.6% 17.5%;
      --muted-foreground: 215 20.2% 65.1%;
      --accent: 217.2 32.6% 17.5%;
      --accent-foreground: 210 40% 98%;
      --destructive: 0 62.8% 30.6%;
      --destructive-foreground: 210 40% 98%;
      --border: 217.2 32.6% 17.5%;
      --input: 217.2 32.6% 17.5%;
      --ring: 224.3 76.3% 94.1%;
      --sidebar-background: 240 5.9% 10%;
      --sidebar-foreground: 240 4.8% 95.9%;
      --sidebar-primary: 224.3 76.3% 94.1%;
      --sidebar-primary-foreground: 240 5.9% 10%;
      --sidebar-accent: 240 3.7% 15.9%;
      --sidebar-accent-foreground: 240 4.8% 95.9%;
      --sidebar-border: 240 3.7% 15.9%;
      --sidebar-ring: 217.2 91.2% 59.8%;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    body {
      background-color: hsl(var(--background));
      color: hsl(var(--foreground));
      display: flex;
      min-height: 100vh;
      transition: background-color 0.3s, color 0.3s;
    }

    .sidebar {
      width: 260px;
      background-color: hsl(var(--sidebar-background));
      border-right: 1px solid hsl(var(--sidebar-border));
      padding: 1.5rem;
      display: flex;
      flex-direction: column;
      transition: all 0.3s;
    }

    .sidebar.collapsed {
      width: 70px;
    }

    .sidebar-header {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 2rem;
    }

    .sidebar-header h2 {
      font-size: 1.25rem;
      font-weight: 600;
      color: hsl(var(--sidebar-foreground));
    }

    .sidebar.collapsed .sidebar-header h2 {
      display: none;
    }

    .logo {
      width: 32px;
      height: 32px;
      background-color: hsl(var(--primary));
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
    }

    .sidebar-nav {
      flex: 1;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.75rem;
      border-radius: var(--radius);
      color: hsl(var(--sidebar-foreground));
      text-decoration: none;
      margin-bottom: 0.25rem;
      transition: background-color 0.2s;
    }

    .nav-item:hover {
      background-color: hsl(var(--sidebar-accent));
      color: hsl(var(--sidebar-accent-foreground));
    }

    .nav-item.active {
      background-color: hsl(var(--sidebar-primary));
      color: hsl(var(--sidebar-primary-foreground));
    }

    .sidebar.collapsed .nav-item span {
      display: none;
    }

    .sidebar-footer {
      padding-top: 1rem;
      border-top: 1px solid hsl(var(--sidebar-border));
    }

    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      border-bottom: 1px solid hsl(var(--border));
      background-color: hsl(var(--card));
    }

    .header-left h1 {
      font-size: 1.5rem;
      font-weight: 600;
    }

    .header-right {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .search-bar {
      display: flex;
      align-items: center;
      background-color: hsl(var(--muted));
      border-radius: var(--radius);
      padding: 0.5rem 1rem;
      width: 300px;
    }

    .search-bar input {
      background: transparent;
      border: none;
      outline: none;
      width: 100%;
      color: hsl(var(--foreground));
    }

    .user-menu {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      cursor: pointer;
    }

    .user-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background-color: hsl(var(--primary));
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 500;
    }

    .content {
      flex: 1;
      padding: 2rem;
      overflow-y: auto;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background-color: hsl(var(--card));
      border: 1px solid hsl(var(--border));
      border-radius: var(--radius);
      padding: 1.5rem;
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    .stat-card-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 0.75rem;
    }

    .stat-card-title {
      font-size: 0.875rem;
      font-weight: 500;
      color: hsl(var(--muted-foreground));
    }

    .stat-card-icon {
      width: 40px;
      height: 40px;
      border-radius: var(--radius);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
    }

    .stat-card-value {
      font-size: 1.875rem;
      font-weight: 700;
      margin-bottom: 0.25rem;
    }

    .stat-card-desc {
      font-size: 0.875rem;
      color: hsl(var(--muted-foreground));
    }

    .chart-container {
      background-color: hsl(var(--card));
      border: 1px solid hsl(var(--border));
      border-radius: var(--radius);
      padding: 1.5rem;
      margin-bottom: 2rem;
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    .chart-header {
      display: flex;
      justify-content: between;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .chart-title {
      font-size: 1.125rem;
      font-weight: 600;
    }

    .users-table-container {
      background-color: hsl(var(--card));
      border: 1px solid hsl(var(--border));
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    .table-header {
      padding: 1.5rem;
      border-bottom: 1px solid hsl(var(--border));
    }

    .table-title {
      font-size: 1.125rem;
      font-weight: 600;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      padding: 1rem 1.5rem;
      text-align: left;
      border-bottom: 1px solid hsl(var(--border));
    }

    th {
      font-weight: 500;
      color: hsl(var(--muted-foreground));
      font-size: 0.875rem;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      padding: 0.25rem 0.75rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 500;
    }

    .status-active {
      background-color: rgba(34, 197, 94, 0.1);
      color: rgb(21, 128, 61);
    }

    .status-inactive {
      background-color: rgba(239, 68, 68, 0.1);
      color: rgb(185, 28, 28);
    }

    .plan-badge {
      display: inline-flex;
      align-items: center;
      padding: 0.25rem 0.75rem;
      border-radius: var(--radius);
      font-size: 0.75rem;
      font-weight: 500;
    }

    .plan-basic {
      background-color: rgba(59, 130, 246, 0.1);
      color: rgb(37, 99, 235);
    }

    .plan-pro {
      background-color: rgba(168, 85, 247, 0.1);
      color: rgb(126, 34, 206);
    }

    .plan-enterprise {
      background-color: rgba(245, 158, 11, 0.1);
      color: rgb(180, 83, 9);
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: var(--radius);
      font-size: 0.875rem;
      font-weight: 500;
      padding: 0.5rem 1rem;
      border: none;
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn-primary {
      background-color: hsl(var(--primary));
      color: hsl(var(--primary-foreground));
    }

    .btn-primary:hover {
      opacity: 0.9;
    }

    .btn-outline {
      background-color: transparent;
      border: 1px solid hsl(var(--border));
      color: hsl(var(--foreground));
    }

    .btn-outline:hover {
      background-color: hsl(var(--accent));
    }

    .btn-sm {
      padding: 0.375rem 0.75rem;
      font-size: 0.75rem;
    }

    .toggle-sidebar {
      background: none;
      border: none;
      cursor: pointer;
      color: hsl(var(--muted-foreground));
      padding: 0.5rem;
      border-radius: var(--radius);
    }

    .toggle-sidebar:hover {
      background-color: hsl(var(--accent));
    }

    .theme-toggle {
      background: none;
      border: none;
      cursor: pointer;
      color: hsl(var(--muted-foreground));
      padding: 0.5rem;
      border-radius: var(--radius);
    }

    .theme-toggle:hover {
      background-color: hsl(var(--accent));
    }

    .tabs {
      display: flex;
      border-bottom: 1px solid hsl(var(--border));
      margin-bottom: 1.5rem;
    }

    .tab {
      padding: 0.75rem 1.5rem;
      background: none;
      border: none;
      cursor: pointer;
      color: hsl(var(--muted-foreground));
      border-bottom: 2px solid transparent;
      transition: all 0.2s;
    }

    .tab.active {
      color: hsl(var(--primary));
      border-bottom-color: hsl(var(--primary));
    }

    .tab:hover {
      color: hsl(var(--foreground));
    }

    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
    }

    .card {
      background-color: hsl(var(--card));
      border: 1px solid hsl(var(--border));
      border-radius: var(--radius);
      padding: 1.5rem;
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    .card-header {
      margin-bottom: 1rem;
    }

    .card-title {
      font-size: 1.125rem;
      font-weight: 600;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-label {
      display: block;
      font-size: 0.875rem;
      font-weight: 500;
      margin-bottom: 0.5rem;
    }

    .form-input {
      width: 100%;
      padding: 0.5rem 0.75rem;
      border: 1px solid hsl(var(--input));
      border-radius: var(--radius);
      background-color: hsl(var(--background));
      color: hsl(var(--foreground));
    }

    .form-input:focus {
      outline: none;
      border-color: hsl(var(--ring));
      box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    .grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
    }

    @media (max-width: 1024px) {
      .sidebar {
        width: 70px;
      }

      .sidebar-header h2,
      .nav-item span {
        display: none;
      }

      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 768px) {
      .stats-grid {
        grid-template-columns: 1fr;
      }

      .grid-2 {
        grid-template-columns: 1fr;
      }

      .search-bar {
        width: 200px;
      }
    }

    @media (max-width: 640px) {
      .sidebar {
        position: fixed;
        left: -260px;
        height: 100%;
        z-index: 50;
      }

      .sidebar.open {
        left: 0;
      }

      .header {
        padding: 1rem;
      }

      .content {
        padding: 1rem;
      }

      .search-bar {
        display: none;
      }
    }
  </style>
  @livewireStyles
</head>

<body class="light">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <div class="logo">S</div>
      <h2>SaaS Admin</h2>
    </div>
    <nav class="sidebar-nav">
      <a href="{{ route('super-admin.dashboard') }}" class="nav-item active">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
      </a>
      <a href="{{ route('super-admin.users') }}" class="nav-item">
        <i class="fas fa-users"></i>
        <span>Users</span>
      </a>
      <a href="{{ route('super-admin.tenants') }}" class="nav-item">
        <i class="fas fa-credit-card"></i>
        <span>Tenants</span>
      </a>
      <a href="{{ route('super-admin.analytics') }}" class="nav-item">
        <i class="fas fa-chart-bar"></i>
        <span>Analytics</span>
      </a>
      <a href="{{ route('super-admin.settings') }}" class="nav-item">
        <i class="fas fa-cog"></i>
        <span>Settings</span>
      </a>
      <a href="{{ route('super-admin.billing') }}" class="nav-item">
        <i class="fas fa-credit-card"></i>
        <span>Billing</span>
      </a>
      <a href="{{ route('super-admin.plans') }}" class="nav-item">
        <i class="fas fa-credit-card"></i>
        <span>Plans</span>
      </a>
      <a href="{{ route('super-admin.support') }}" class="nav-item">
        <i class="fas fa-question-circle"></i>
        <span>Support</span>
      </a>
    </nav>
    <div class="sidebar-footer">
      <form action="{{ route('super-admin.logout') }}" method="POST" class="nav-item">
        @csrf
        <i class="fas fa-sign-out-alt"></i>
        <button class="text-sm text-gray-700 hover:text-gray-900" type="submit">Logout</button>
      </form>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Header -->
    <header class="header">
      <div class="header-left">
        <button class="toggle-sidebar">
          <i class="fas fa-bars"></i>
        </button>
        <h1>Dashboard</h1>
      </div>
      <div class="header-right">
        <div class="search-bar">
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Search...">
        </div>
        <button class="theme-toggle">
          <i class="fas fa-moon"></i>
        </button>
        <div class="user-menu">
          <div class="user-avatar">JD</div>
          <span>John Doe</span>
        </div>
      </div>
    </header>

    <!-- Content -->
    <div class="content">
      {{ $slot }}
    </div>
  </div>

  @yield('scripts')
  @stack('scripts')
  @livewireScripts

  <script src="{{ asset('js/script.js') }}"></script>
  {{-- <script src="{{ asset('js/app.js') }}"></script> --}}

  @vite('resources/js/app.js');

  <script>
    // Toggle sidebar
        document.querySelector('.toggle-sidebar').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });

        // Toggle theme
        document.querySelector('.theme-toggle').addEventListener('click', function() {
            document.body.classList.toggle('dark');
            const icon = this.querySelector('i');
            if (document.body.classList.contains('dark')) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
        });

        // Tab functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs and tab contents
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Chart initialization
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue ($)',
                    data: [12000, 19000, 15000, 25000, 22000, 30000],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Mobile sidebar toggle
        if (window.innerWidth <= 640) {
            document.querySelector('.toggle-sidebar').addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('open');
            });
        }
  </script>
</body>

</html>