<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
            --danger: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);
            --dark: #1a1d29;
            --light: #f8fafc;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: var(--gray-700);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        /* Modern Sidebar */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            box-shadow: var(--shadow-2xl);
        }
        
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.02) 0%, transparent 50%),
                              radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.02) 0%, transparent 50%);
        }
        
        .sidebar-brand {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 2;
        }
        
        .brand-logo {
            width: 48px;
            height: 48px;
            background: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }
        
        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 16px;
        }
        
        .brand-text h5 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: white;
            margin: 0;
            font-size: 1.25rem;
        }
        
        .brand-text small {
            color: var(--gray-400);
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .sidebar .nav-link {
            color: var(--gray-300);
            padding: 16px 24px;
            border-radius: 12px;
            margin: 4px 16px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            font-weight: 500;
            font-size: 0.9rem;
            z-index: 2;
        }
        
        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--primary);
            transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: -1;
            border-radius: 12px;
        }
        
        .sidebar .nav-link:hover::before,
        .sidebar .nav-link.active::before {
            left: 0;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            transform: translateX(4px);
            box-shadow: var(--shadow-lg);
        }
        
        .sidebar .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }
        
        /* Main Content Area */
        .main-content {
            background: var(--light);
            min-height: 100vh;
            position: relative;
        }
        
        .top-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow-sm);
        }
        
        .page-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--gray-800);
            margin: 0;
            font-size: 1.875rem;
        }
        
        .page-subtitle {
            color: var(--gray-500);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        /* Modern Cards */
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-2xl);
        }
        
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--gray-200);
            padding: 24px;
            font-weight: 600;
        }
        
        /* Modern Buttons */
        .btn {
            border-radius: 14px;
            font-weight: 600;
            padding: 12px 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            font-size: 0.875rem;
            letter-spacing: 0.025em;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: var(--shadow-md);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        }
        
        .btn-success {
            background: var(--success);
            color: white;
            box-shadow: var(--shadow-md);
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
        }
        
        .btn-outline-primary {
            border: 2px solid transparent;
            background: linear-gradient(white, white) padding-box, var(--primary) border-box;
            color: #667eea;
            font-weight: 600;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }
        
        /* Form Elements */
        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid var(--gray-200);
            padding: 12px 16px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }
        
        /* Modern Tables */
        .table {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            background: white;
        }
        
        .table thead th {
            background: linear-gradient(135deg, var(--gray-100) 0%, var(--gray-50) 100%);
            border: none;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            padding: 20px;
            color: var(--gray-700);
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
            border: none;
        }
        
        .table tbody tr:hover {
            background: linear-gradient(135deg, var(--gray-50) 0%, rgba(102, 126, 234, 0.02) 100%);
            transform: scale(1.01);
            box-shadow: var(--shadow-md);
        }
        
        .table tbody td {
            padding: 16px 20px;
            border: none;
            vertical-align: middle;
        }
        
        /* Status Badges */
        .badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        
        /* Alerts */
        .alert {
            border-radius: 16px;
            border: none;
            padding: 20px;
            margin-bottom: 24px;
            backdrop-filter: blur(10px);
        }
        
        .alert-success {
            background: linear-gradient(135deg, rgba(74, 222, 128, 0.1) 0%, rgba(34, 197, 94, 0.1) 100%);
            border-left: 4px solid #22c55e;
            color: #065f46;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, rgba(248, 113, 113, 0.1) 0%, rgba(239, 68, 68, 0.1) 100%);
            border-left: 4px solid #ef4444;
            color: #991b1b;
        }
        
        /* Stat Cards */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 32px;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--gray-100);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--primary);
        }
        
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-2xl);
        }
        
        .stat-icon {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 20px;
        }
        
        /* Animations */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        /* Gradient Backgrounds */
        .bg-gradient-primary {
            background: var(--primary) !important;
        }
        
        .bg-gradient-success {
            background: var(--success) !important;
        }
        
        .bg-gradient-secondary {
            background: var(--secondary) !important;
        }
        
        .bg-gradient-warning {
            background: var(--warning) !important;
        }
        
        .bg-gradient-danger {
            background: var(--danger) !important;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(45deg, #5a67d8, #6b46c1);
        }
        
        /* Dropdown Menus */
        .dropdown-menu {
            border-radius: 16px;
            border: none;
            box-shadow: var(--shadow-2xl);
            padding: 12px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
        }
        
        .dropdown-item {
            border-radius: 8px;
            padding: 12px 16px;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .dropdown-item:hover {
            background: var(--gray-100);
            transform: translateX(4px);
        }
        
        /* Notification Bell */
        .notification-bell {
            position: relative;
        }
        
        .notification-bell::after {
            content: '';
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                width: 280px;
                height: 100vh;
                z-index: 1050;
                transition: left 0.3s ease;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                display: none;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
            
            .main-content {
                padding-left: 0;
                width: 100%;
            }
            
            .mobile-menu-btn {
                display: block !important;
            }
            
            .top-navbar {
                padding: 1rem;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .brand-text h5 {
                font-size: 1.1rem;
            }
            
            .brand-text small {
                font-size: 0.75rem;
            }
            
            .nav-link {
                padding: 1rem 1.5rem;
                font-size: 0.95rem;
            }
            
            .container-fluid {
                padding: 0;
            }
        }
        
        @media (max-width: 576px) {
            .page-title {
                font-size: 1.25rem;
            }
            
            .top-navbar {
                padding: 0.75rem;
            }
            
            .card {
                margin-bottom: 1rem;
            }
            
            .btn-sm {
                font-size: 0.75rem;
                padding: 0.375rem 0.75rem;
            }
            
            .table-responsive {
                font-size: 0.85rem;
            }
            
            .dropdown-menu {
                font-size: 0.85rem;
            }
            
            .mobile-card .card-body {
                padding: 0.75rem;
            }
            
            .mobile-card .card-title {
                font-size: 1rem;
            }
            
            .text-sm {
                font-size: 0.8rem;
            }
        }
        
        /* Mobile specific utilities */
        .mobile-hidden {
            display: none;
        }
        
        @media (min-width: 992px) {
            .mobile-hidden {
                display: block;
            }
            
            .desktop-hidden {
                display: none;
            }
        }
        
        @media (max-width: 991px) {
            .desktop-hidden {
                display: block;
            }
        }
        
        .mobile-menu-btn {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <!-- Mobile Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <div class="row g-0">
            <!-- Modern Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar px-0" id="sidebar">
                <div class="sidebar-brand">
                    <div class="d-flex align-items-center">
                        <div class="brand-logo me-3">
                            <img src="{{ asset('images/me_logo2.png') }}" alt="Mariya Electronics Logo">
                        </div>
                        <div class="brand-text">
                            <h5>Mariya Electronics</h5>
                            <small>Electronics Management</small>
                        </div>
                    </div>
                </div>
                
                <nav class="nav flex-column py-4">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="fas fa-microchip"></i> Products
                    </a>
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                        <i class="fas fa-tags"></i> Categories
                    </a>
                    <a class="nav-link {{ request()->routeIs('brands.*') ? 'active' : '' }}" href="{{ route('brands.index') }}">
                        <i class="fas fa-copyright"></i> Brands
                    </a>
                    <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                        <i class="fas fa-user-friends"></i> Customers
                    </a>
                    <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                        <i class="fas fa-shopping-bag"></i> Sales
                    </a>
                    <a class="nav-link {{ request()->routeIs('installments.*') ? 'active' : '' }}" href="{{ route('installments.index') }}">
                        <i class="fas fa-calendar-check"></i> Installments
                    </a>
                    <a class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                        <i class="fas fa-credit-card"></i> Payments
                    </a>
                    <hr class="my-4 mx-4" style="border-color: rgba(255, 255, 255, 0.1);">
                    <a class="nav-link" href="#">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-question-circle"></i> Help & Support
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Top Navigation -->
                <div class="top-navbar">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <!-- Mobile Menu Button -->
                            <button class="btn btn-outline-primary me-3 mobile-menu-btn" id="mobileMenuBtn" type="button">
                                <i class="fas fa-bars"></i>
                            </button>
                            <div>
                                <h1 class="page-title mb-0">@yield('page-title', 'Dashboard')</h1>
                                @hasSection('page-subtitle')
                                    <p class="page-subtitle mb-0">@yield('page-subtitle')</p>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            @yield('header-actions')
                            <div class="notification-bell">
                                <button class="btn btn-outline-primary rounded-circle p-2" style="width: 48px; height: 48px;">
                                    <i class="fas fa-bell"></i>
                                </button>
                            </div>
                            <div class="dropdown">
                                <button class="btn bg-gradient-primary text-white dropdown-toggle border-0 d-flex align-items-center" 
                                        type="button" data-bs-toggle="dropdown" style="border-radius: 24px; padding: 8px 20px;">
                                    <div class="bg-white rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                         style="width: 32px; height: 32px;">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <span class="fw-medium">Admin</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-chart-bar me-2"></i>Analytics</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="container-fluid p-4 fade-in">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-3 text-success"></i>
                                <div>
                                    <strong>Success!</strong> {{ session('success') }}
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-3 text-danger"></i>
                                <div>
                                    <strong>Error!</strong> {{ session('error') }}
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex">
                                <i class="fas fa-exclamation-circle me-3 text-danger mt-1"></i>
                                <div>
                                    <strong>Validation Errors:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mobile Sidebar JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            function toggleSidebar() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            }
            
            function closeSidebar() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            }
            
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', toggleSidebar);
            }
            
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }
            
            // Close sidebar when clicking nav links on mobile
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        closeSidebar();
                    }
                });
            });
            
            // Close sidebar on window resize if mobile
            window.addEventListener('resize', () => {
                if (window.innerWidth > 768) {
                    closeSidebar();
                }
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>