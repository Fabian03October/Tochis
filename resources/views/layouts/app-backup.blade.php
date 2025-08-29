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
        
        .btn-primary {
            @apply bg-orange-500 hover:bg-orange-600 text-white font-medium py-2.5 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg;
        }
        
        .btn-secondary {
            @apply bg-gray-600 hover:bg-gray-700 text-white font-medium py-2.5 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg;
        }
        
        .btn-success {
            @apply bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg;
        }
        
        .btn-danger {
            @apply bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg;
        }
        
        .btn-warning {
            @apply bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2.5 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg;
        }
        
        .tochis-gradient {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #dc2626 100%);
            position: relative;
            overflow: hidden;
        }
        
        .tochis-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .sidebar-logo {
            position: relative;
            z-index: 2;
        }
        
        .sidebar-logo h1 {
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 2px;
        }
        
        .sidebar-logo i {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
            animation: pulse-glow 4s ease-in-out infinite;
        }
        
        @keyframes pulse-glow {
            0%, 100% { filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3)); }
            50% { filter: drop-shadow(0 2px 8px rgba(255, 255, 255, 0.5)); }
        }
        
        .sidebar-user-section {
            background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-user-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #f97316, transparent);
        }
        
        .sidebar-item {
            @apply flex items-center px-6 py-4 text-gray-300 hover:bg-gray-800 hover:text-orange-400 transition-all duration-300 group relative;
            border-left: 4px solid transparent;
            text-decoration: none;
            display: flex;
            width: 100%;
            min-height: 60px;
            margin-bottom: 4px;
            border-radius: 0 16px 16px 0;
            margin-right: 12px;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, rgba(249, 115, 22, 0.3) 0%, rgba(249, 115, 22, 0.1) 50%, transparent 100%);
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 0;
        }
        
        .sidebar-item:hover::before {
            width: 100%;
        }
        
        .sidebar-item.active {
            @apply bg-gray-800 text-orange-400;
            border-left: 6px solid #f97316;
            background: linear-gradient(90deg, rgba(249, 115, 22, 0.25) 0%, rgba(249, 115, 22, 0.15) 50%, rgba(249, 115, 22, 0.05) 100%);
            box-shadow: 0 4px 20px rgba(249, 115, 22, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            transform: translateX(2px);
        }
        
        .sidebar-item.active::before {
            width: 100%;
        }
        
        .sidebar-item i {
            @apply text-center transition-all duration-300 flex-shrink-0;
            min-width: 28px;
            width: 28px;
            height: 28px;
            margin-right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }
        
        .sidebar-item:hover i {
            @apply transform scale-110 text-orange-400;
            background: rgba(249, 115, 22, 0.2);
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        }
        
        .sidebar-item.active i {
            @apply text-orange-300;
            background: rgba(249, 115, 22, 0.3);
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.4);
            transform: scale(1.1);
        }
        
        .sidebar-item span {
            @apply font-semibold text-base flex-1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.5;
            position: relative;
            z-index: 1;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .sidebar-item:hover span {
            transform: translateX(4px);
            color: #fb923c;
        }
        
        .sidebar-item.active span {
            color: #fb923c;
            font-weight: 600;
        }
        
        .sidebar-item:hover {
            transform: translateX(6px);
            box-shadow: 0 8px 25px rgba(249, 115, 22, 0.3);
        }
        
        .sidebar-section-title {
            @apply px-6 py-5 border-b border-gray-700 mb-4;
            position: relative;
        }
        
        .sidebar-section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 24px;
            right: 24px;
            height: 2px;
            background: linear-gradient(90deg, #f97316 0%, rgba(249, 115, 22, 0.3) 50%, transparent 100%);
        }
        
        .sidebar-section-title h3 {
            @apply text-sm font-bold text-orange-400 uppercase tracking-wider flex items-center;
            letter-spacing: 1.5px;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }
        
        .sidebar-menu-group {
            @apply space-y-2 px-3 mb-8;
        }
        
        /* Asegurar que el sidebar esté siempre fijo */
        .sidebar-fixed {
            position: fixed !important;
            left: 0 !important;
            top: 0 !important;
            height: 100vh !important;
            z-index: 1000 !important;
            width: 280px !important;
            background: linear-gradient(180deg, #111827 0%, #1f2937 50%, #111827 100%);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(249, 115, 22, 0.2);
        }
        
        /* Asegurar que el contenido principal tenga el margen correcto */
        .main-content {
            margin-left: 280px !important;
        }
        
        /* Scroll personalizado para el sidebar */
        .sidebar-fixed nav::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-fixed nav::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        
        .sidebar-fixed nav::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #f97316, #ea580c);
            border-radius: 10px;
        }
        
        .sidebar-fixed nav::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #fb923c, #f97316);
        }
        
        /* Efecto de brillo en elementos activos */
        @keyframes glow-pulse {
            0%, 100% { box-shadow: 0 4px 20px rgba(249, 115, 22, 0.4); }
            50% { box-shadow: 0 6px 30px rgba(249, 115, 22, 0.6); }
        }
        
        .sidebar-item.active {
            animation: glow-pulse 3s ease-in-out infinite;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex">
        @auth
        <!-- Sidebar -->
        <div class="sidebar-fixed shadow-2xl flex flex-col">
            <!-- Logo y Título -->
            <div class="h-24 tochis-gradient flex items-center justify-center flex-shrink-0 shadow-xl">
                <div class="text-center sidebar-logo">
                    <h1 class="text-white text-3xl font-bold tracking-wider flex items-center justify-center">
                        <i class="fas fa-utensils mr-4 text-3xl"></i>
                        TOCHIS
                    </h1>
                    <p class="text-orange-100 text-sm font-medium mt-2 tracking-wide">Sistema de Punto de Venta</p>
                </div>
            </div>
            
            <nav class="flex-1 mt-8 overflow-y-auto">
                @if(auth()->user()->isAdmin())
                    <!-- Menú Administrador -->
                    <div class="sidebar-section-title">
                        <h3>
                            <i class="fas fa-crown mr-4 text-lg"></i>
                            Administración
                        </h3>
                    </div>
                    
                    <div class="sidebar-menu-group">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Dashboard</span>
                        </a>
                        
                        <a href="{{ route('admin.categories.index') }}" 
                           class="sidebar-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="fas fa-tags"></i>
                            <span>Categorías</span>
                        </a>
                        
                        <a href="{{ route('admin.products.index') }}" 
                           class="sidebar-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="fas fa-hamburger"></i>
                            <span>Productos</span>
                        </a>
                        
                        <a href="{{ route('admin.customization-options.index') }}" 
                           class="sidebar-item {{ request()->routeIs('admin.customization-options.*') ? 'active' : '' }}">
                            <i class="fas fa-cogs"></i>
                            <span>Personalización</span>
                        </a>
                        
                        <a href="{{ route('admin.promotions.index') }}" 
                           class="sidebar-item {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                            <i class="fas fa-fire"></i>
                            <span>Promociones</span>
                        </a>
                        
                        <a href="{{ route('admin.combos.index') }}" 
                           class="sidebar-item {{ request()->routeIs('admin.combos.*') ? 'active' : '' }}">
                            <i class="fas fa-box-open"></i>
                            <span>Combos</span>
                        </a>
                        
                        <a href="{{ route('admin.reports.index') }}" 
                           class="sidebar-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Reportes</span>
                        </a>
                    </div>
                @else
                    <!-- Menú Cajero -->
                    <div class="sidebar-section-title">
                        <h3>
                            <i class="fas fa-cash-register mr-4 text-lg"></i>
                            Punto de Venta
                        </h3>
                    </div>
                    
                    <div class="sidebar-menu-group">
                        <a href="{{ route('cashier.dashboard') }}" 
                           class="sidebar-item {{ request()->routeIs('cashier.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Dashboard</span>
                        </a>
                        
                        <a href="{{ route('cashier.sale.index') }}" 
                           class="sidebar-item {{ request()->routeIs('cashier.sale.index') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Nueva Venta</span>
                        </a>
                        
                        <a href="{{ route('cashier.sale.history') }}" 
                           class="sidebar-item {{ request()->routeIs('cashier.sale.history') ? 'active' : '' }}">
                            <i class="fas fa-receipt"></i>
                            <span>Historial de Ventas</span>
                        </a>
                        
                        <a href="{{ route('cashier.cash-cut.index') }}" 
                           class="sidebar-item {{ request()->routeIs('cashier.cash-cut.*') ? 'active' : '' }}">
                            <i class="fas fa-calculator"></i>
                            <span>Corte de Caja</span>
                        </a>
                    </div>
                @endif
            </nav>
            
            <!-- Usuario y Logout -->
            <div class="flex-shrink-0 p-6 sidebar-user-section border-t border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center flex-1 min-w-0">
                        <div class="w-14 h-14 tochis-gradient rounded-full flex items-center justify-center text-white text-lg font-bold shadow-xl flex-shrink-0 ring-4 ring-orange-400/20">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="ml-4 min-w-0 flex-1">
                            <p class="text-base font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-orange-400 font-medium tracking-wide">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline flex-shrink-0 ml-4">
                        @csrf
                        <button type="submit" class="w-12 h-12 bg-gray-700 hover:bg-orange-500 text-gray-400 hover:text-white rounded-xl transition-all duration-300 transform hover:scale-110 hover:rotate-6 flex items-center justify-center shadow-lg hover:shadow-xl" title="Cerrar Sesión">
                            <i class="fas fa-sign-out-alt text-xl"></i>
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
                    <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded-lg shadow-sm fade-in" role="alert">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-orange-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-orange-800 font-medium">{{ session('warning') }}</p>
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
