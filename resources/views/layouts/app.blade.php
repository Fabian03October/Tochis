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
            background: linear-gradient(135deg, var(--tochis-orange) 0%, var(--tochis-orange-dark) 100%);
        }
        
        .sidebar-item {
            @apply flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-orange-400 transition-all duration-200 group;
        }
        
        .sidebar-item.active {
            @apply bg-gray-800 text-orange-400 border-r-4 border-orange-500;
        }
        
        .sidebar-item i {
            @apply w-5 h-5 mr-3 transition-all duration-200;
        }
        
        .sidebar-item:hover i {
            @apply transform scale-110;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex">
        @auth
        <!-- Sidebar -->
        <div class="w-64 bg-gray-900 shadow-2xl">
            <!-- Logo y Título -->
            <div class="h-16 tochis-gradient flex items-center justify-center">
                <div class="text-center">
                    <h1 class="text-white text-2xl font-bold tracking-wider">
                        <i class="fas fa-utensils mr-2"></i>
                        TOCHIS
                    </h1>
                    <p class="text-orange-100 text-xs font-medium">Sistema de Punto de Venta</p>
                </div>
            </div>
            
            <nav class="mt-8">
                @if(auth()->user()->isAdmin())
                    <!-- Menú Administrador -->
                    <div class="px-6 py-3">
                        <h3 class="text-xs font-bold text-orange-400 uppercase tracking-wider">Administración</h3>
                    </div>
                    
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.categories.index') }}" 
                       class="sidebar-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        Categorías
                    </a>
                    
                    <a href="{{ route('admin.products.index') }}" 
                       class="sidebar-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="fas fa-hamburger"></i>
                        Productos
                    </a>
                    
                    <a href="{{ route('admin.customization-options.index') }}" 
                       class="sidebar-item {{ request()->routeIs('admin.customization-options.*') ? 'active' : '' }}">
                        <i class="fas fa-cogs"></i>
                        Personalización
                    </a>
                    
                    <a href="{{ route('admin.promotions.index') }}" 
                       class="sidebar-item {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                        <i class="fas fa-fire"></i>
                        Promociones
                    </a>
                    
                    <a href="{{ route('admin.combos.index') }}" 
                       class="sidebar-item {{ request()->routeIs('admin.combos.*') ? 'active' : '' }}">
                        <i class="fas fa-box-open"></i>
                        Combos
                    </a>
                    
                    <a href="{{ route('admin.reports.index') }}" 
                       class="sidebar-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        Reportes
                    </a>
                @else
                    <!-- Menú Cajero -->
                    <div class="px-6 py-3">
                        <h3 class="text-xs font-bold text-orange-400 uppercase tracking-wider">Punto de Venta</h3>
                    </div>
                    
                    <a href="{{ route('cashier.dashboard') }}" 
                       class="sidebar-item {{ request()->routeIs('cashier.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('cashier.sale.index') }}" 
                       class="sidebar-item {{ request()->routeIs('cashier.sale.index') ? 'active' : '' }}">
                        <i class="fas fa-cash-register"></i>
                        Nueva Venta
                    </a>
                    
                    <a href="{{ route('cashier.sale.history') }}" 
                       class="sidebar-item {{ request()->routeIs('cashier.sale.history') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i>
                        Historial de Ventas
                    </a>
                    
                    <a href="{{ route('cashier.cash-cut.index') }}" 
                       class="sidebar-item {{ request()->routeIs('cashier.cash-cut.*') ? 'active' : '' }}">
                        <i class="fas fa-calculator"></i>
                        Corte de Caja
                    </a>
                @endif
            </nav>
            
            <!-- Usuario y Logout -->
            <div class="absolute bottom-0 w-64 p-4 bg-gray-800 border-t border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 tochis-gradient rounded-full flex items-center justify-center text-white text-sm font-bold shadow-lg">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-orange-400 font-medium">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-orange-400 transition-all duration-200 transform hover:scale-110" title="Cerrar Sesión">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endauth

        <!-- Main Content -->
        <div class="flex-1 @auth ml-0 @else mx-auto @endauth">
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
            <main class="@auth p-8 @else p-0 @endauth">
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
