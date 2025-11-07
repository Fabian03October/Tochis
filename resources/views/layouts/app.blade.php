<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TOCHIS - Sistema POS')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --tochis-orange: #f97316;
            --tochis-orange-dark: #ea580c;
            --tochis-orange-light: #fb923c;
            --tochis-orange-lightest: #fed7aa;
            --tochis-black: #1a1a1a;
            --tochis-gray: #f8fafc;
            --tochis-gray-dark: #64748b;
        }
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Sidebar principal con gradiente naranja */
        .sidebar-fixed {
            position: fixed !important;
            left: 0 !important;
            top: 0 !important;
            height: 100vh !important;
            width: 280px !important;
            z-index: 1000 !important;
            background: linear-gradient(180deg, #f97316 0%, #ea580c 50%, #dc2626 100%);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        }
        
        /* Elementos del menú estilo FoodMeal */
        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            margin: 8px 16px;
            border-radius: 16px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            font-size: 15px;
            position: relative;
            backdrop-filter: blur(10px);
        }
        
        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        .sidebar-item.active {
            background: rgba(255, 255, 255, 0.95);
            color: #f97316;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            transform: translateY(-1px);
        }
        
        /* Iconos grandes y elegantes */
        .sidebar-item .icon-container {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        
        .sidebar-item:hover .icon-container {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }
        
        .sidebar-item.active .icon-container {
            background: #f97316;
            color: white;
            transform: scale(1.05);
        }
        
        .sidebar-item .icon-container i {
            font-size: 20px;
            transition: all 0.3s ease;
        }
        
        /* Texto del menú */
        .sidebar-item .menu-text {
            flex: 1;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .sidebar-item.active .menu-text {
            font-weight: 600;
        }
        
        /* Sección de títulos */
        .sidebar-section-title {
            padding: 20px 20px 12px 20px;
            margin-bottom: 8px;
        }
        
        .sidebar-section-title h3 {
            color: rgba(255, 255, 255, 0.9);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: flex;
            align-items: center;
        }
        
        .sidebar-section-title h3 i {
            margin-right: 12px;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.7);
        }
        
        /* Grupo de menú */
        .sidebar-menu-group {
            margin-bottom: 32px;
        }
        
        /* Logo área */
        .logo-area {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Usuario área */
        .user-area {
            background: rgba(0, 0, 0, 0.1);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .user-avatar {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
        }
        
        .logout-btn {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: scale(1.05);
        }
        
        /* Contenido principal */
        .main-content {
            margin-left: 280px !important;
        }
        
        /* Scrollbar personalizada */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* Sistema de botones uniforme */
        .btn-primary {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 12px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(249, 115, 22, 0.3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(249, 115, 22, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .btn-secondary {
            background: white;
            color: #6b7280;
            border: 1px solid #d1d5db;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }
        
        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #9ca3af;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: #4b5563;
            text-decoration: none;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 12px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 12px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 12px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
            color: white;
            text-decoration: none;
        }
        
        /* Tamaños de botones */
        .btn-sm {
            padding: 8px 16px;
            font-size: 12px;
            border-radius: 8px;
        }
        
        .btn-lg {
            padding: 16px 32px;
            font-size: 16px;
            border-radius: 16px;
        }
        
        /* Botones disabled */
        .btn-primary:disabled, .btn-secondary:disabled, .btn-success:disabled, .btn-danger:disabled, .btn-warning:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex">
        @auth
        <!-- Sidebar -->
        <div class="sidebar-fixed flex flex-col">
            <!-- Logo y Título -->
            <div class="h-20 logo-area flex items-center justify-center flex-shrink-0">
                <div class="text-center">
                    <h1 class="text-white text-2xl font-bold tracking-wider flex items-center justify-center">
                        <i class="fas fa-utensils mr-3 text-2xl"></i>
                        TOCHIS
                    </h1>
                    <p class="text-white text-xs font-medium mt-1 opacity-80">Sistema de Punto de Venta</p>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 py-6 overflow-y-auto custom-scrollbar">
                @if(auth()->user()->isAdmin())
                    <!-- 1. Dashboard -->
                    <div class="sidebar-section-title">
                        <h3>
                            <i class="fas fa-chart-line"></i>
                            Panel Principal
                        </h3>
                    </div>
                    
                    <div class="sidebar-menu-group">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="sidebar-item {{ request()->routeIs('admin.dashboard') || request()->route()->getName() == 'admin.dashboard' ? 'active' : '' }}">
                            <div class="icon-container">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </div>

                    <!-- 2. Ajustes de POS -->
                    <div class="sidebar-section-title">
                        <h3>
                            <i class="fas fa-store"></i>
                            Ajustes de POS
                        </h3>
                    </div>
                    
                    <div class="sidebar-menu-group">
                        <a href="{{ route('admin.categories.index') }}" 
                           class="sidebar-item {{ request()->routeIs('admin.categories.*') || str_contains(request()->url(), 'admin/categories') ? 'active' : '' }}">
                            <div class="icon-container">
                                <i class="fas fa-tags"></i>
                            </div>
                            <span class="menu-text">Categorías</span>
                        </a>
                        
                        <a href="{{ route('admin.customization-options.index') }}" 
                           class="sidebar-item {{ request()->routeIs('admin.customization-options.*') || str_contains(request()->url(), 'customization-options') ? 'active' : '' }}">
                            <div class="icon-container">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <span class="menu-text">Personalización</span>
                        </a>
                        
                        <a href="{{ route('admin.products.index') }}" 
                           class="sidebar-item {{ request()->routeIs('admin.products.*') || str_contains(request()->url(), 'admin/products') ? 'active' : '' }}">
                            <div class="icon-container">
                                <i class="fas fa-hamburger"></i>
                            </div>
                            <span class="menu-text">Platillos</span>
                        </a>
                        
                        <a href="{{ route('admin.combos.index') }}" 
                           class="sidebar-item {{ request()->routeIs('admin.combos.*') || str_contains(request()->url(), 'admin/combos') ? 'active' : '' }}">
                            <div class="icon-container">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <span class="menu-text">Combos</span>
                        </a>
                        
                        <a href="{{ route('admin.promotions.index') }}" 
                           class="sidebar-item {{ request()->routeIs('admin.promotions.*') || str_contains(request()->url(), 'admin/promotions') ? 'active' : '' }}">
                            <div class="icon-container">
                                <i class="fas fa-fire"></i>
                            </div>
                            <span class="menu-text">Promociones</span>
                        </a>
                    </div>

                    <!-- 3. Ajustes del Sistema -->
                    <div class="sidebar-section-title">
                        <h3>
                            <i class="fas fa-cog"></i>
                            Ajustes del Sistema
                        </h3>
                    </div>
                    
                    <div class="sidebar-menu-group">
                        <a href="{{ route('admin.printers.index') }}" 
                           class="sidebar-item {{ request()->routeIs('admin.printers.*') || str_contains(request()->url(), 'admin/printers') ? 'active' : '' }}">
                            <div class="icon-container">
                                <i class="fas fa-print"></i>
                            </div>
                            <span class="menu-text">Impresoras</span>
                        </a>
                        
                        <a href="{{ route('admin.mercadopago.index') }}" 
                           class="sidebar-item {{ request()->routeIs('admin.mercadopago.*') || str_contains(request()->url(), 'admin/mercadopago') ? 'active' : '' }}">
                            <div class="icon-container">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <span class="menu-text">Terminal de Pago</span>
                        </a>
                    </div>

                    <!-- 4. Reportes -->
                    <div class="sidebar-section-title">
                        <h3>
                            <i class="fas fa-chart-bar"></i>
                            Reportes
                        </h3>
                    </div>
                    
                    <div class="sidebar-menu-group">
                        <a href="{{ route('admin.reports.index') }}" 
                           class="sidebar-item {{ request()->routeIs('admin.reports.*') || str_contains(request()->url(), 'admin/reports') ? 'active' : '' }}">
                            <div class="icon-container">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <span class="menu-text">Reportes</span>
                        </a>
                    </div>
                @else
                    <!-- Menú Cajero -->
                    <div class="sidebar-section-title">
                        <h3>
                            <i class="fas fa-cash-register"></i>
                            Punto de Venta
                        </h3>
                    </div>
                    
                    <div class="sidebar-menu-group">
                        <a href="{{ route('cashier.dashboard') }}" 
                           class="sidebar-item {{ request()->routeIs('cashier.dashboard') || request()->route()->getName() == 'cashier.dashboard' ? 'active' : '' }}">
                            <div class="icon-container">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <span class="menu-text">Dashboard</span>
                        </a>
                        
                        <a href="{{ route('cashier.sale.index') }}" 
                           class="sidebar-item {{ request()->routeIs('cashier.sale.*') || str_contains(request()->url(), 'cashier/sale') ? 'active' : '' }}">
                            <div class="icon-container">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <span class="menu-text">Nueva Venta</span>
                        </a>
                        
                        <a href="{{ route('cashier.sale.history') }}" 
                           class="sidebar-item {{ request()->routeIs('cashier.sale.history') || str_contains(request()->url(), 'sales/history') ? 'active' : '' }}">
                            <div class="icon-container">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <span class="menu-text">Historial de Ventas</span>
                        </a>
                        
                        <a href="{{ route('cashier.cash-cut.index') }}" 
                           class="sidebar-item {{ request()->routeIs('cashier.cash-cut.*') || str_contains(request()->url(), 'cash-cut') ? 'active' : '' }}">
                            <div class="icon-container">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <span class="menu-text">Corte de Caja</span>
                        </a>
                    </div>
                @endif
            </nav>
            
            <!-- Usuario y Logout -->
            <div class="user-area p-4 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center flex-1 min-w-0">
                        <div class="user-avatar">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="ml-3 min-w-0 flex-1">
                            <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                            <p class="text-white text-xs opacity-70 font-medium">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline flex-shrink-0 ml-3">
                        @csrf
                        <button type="submit" class="logout-btn" title="Cerrar Sesión">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endauth

        <!-- Main Content -->
        <div class="main-content flex-1">
            @auth
            <!-- Header -->
            <div class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                            @hasSection('breadcrumb')
                                <nav class="text-sm text-gray-500 mt-2 flex items-center">
                                    <i class="fas fa-home mr-2"></i>
                                    @yield('breadcrumb')
                                </nav>
                            @endif
                        </div>
                        
                        @hasSection('header-actions')
                            <div class="flex space-x-3">
                                @yield('header-actions')
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endauth

            <!-- Alerts -->
            @if(session('success'))
                <div class="mx-8 mt-6">
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow-sm fade-in" role="alert">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-green-800 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-8 mt-6">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-sm fade-in" role="alert">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-red-800 font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="mx-8 mt-6">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg shadow-sm fade-in" role="alert">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-yellow-800 font-medium">{{ session('warning') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main class="@auth p-8 @else p-0 min-h-screen flex items-center justify-center @endauth">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('[role="alert"]').fadeOut();
        }, 5000);
        
        // CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Enhanced sidebar active state management
        function updateSidebarActiveStates() {
            const currentUrl = window.location.href;
            const currentPath = window.location.pathname;
            
            // Remove all active states first
            $('.sidebar-item').removeClass('active');
            
            // Find and activate the correct menu item
            $('.sidebar-item').each(function() {
                const $item = $(this);
                const href = $item.attr('href');
                
                if (href && (currentUrl === href || currentPath === new URL(href, window.location.origin).pathname)) {
                    $item.addClass('active');
                    return false; // Break the loop
                }
            });
            
            // If no exact match, try pattern matching
            if (!$('.sidebar-item.active').length) {
                $('.sidebar-item').each(function() {
                    const $item = $(this);
                    const href = $item.attr('href');
                    
                    if (href) {
                        const urlPath = new URL(href, window.location.origin).pathname;
                        const pathSegments = urlPath.split('/').filter(segment => segment.length > 0);
                        const currentSegments = currentPath.split('/').filter(segment => segment.length > 0);
                        
                        // Check if current path contains the menu item's path segments
                        if (pathSegments.length > 0 && currentSegments.length >= pathSegments.length) {
                            let isMatch = true;
                            for (let i = 0; i < pathSegments.length; i++) {
                                if (pathSegments[i] !== currentSegments[i]) {
                                    isMatch = false;
                                    break;
                                }
                            }
                            if (isMatch) {
                                $item.addClass('active');
                                return false; // Break the loop
                            }
                        }
                    }
                });
            }
        }
        
        // Run on page load
        updateSidebarActiveStates();
        
        // Update on navigation (for SPA-like behavior)
        $(window).on('popstate', updateSidebarActiveStates);
        
        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function(event) {
            var target = $(this.getAttribute('href'));
            if( target.length ) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 80
                }, 1000);
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
