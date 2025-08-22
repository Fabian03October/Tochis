<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema POS')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .btn-primary {
            @apply bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200;
        }
        
        .btn-secondary {
            @apply bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200;
        }
        
        .btn-success {
            @apply bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200;
        }
        
        .btn-danger {
            @apply bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200;
        }
        
        .btn-warning {
            @apply bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex">
        @auth
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <div class="flex items-center justify-center h-16 bg-blue-600 text-white">
                <h1 class="text-xl font-bold">
                    <i class="fas fa-cash-register mr-2"></i>
                    Sistema POS
                </h1>
            </div>
            
            <nav class="mt-6">
                @if(auth()->user()->isAdmin())
                    <!-- Menú Administrador -->
                    <div class="px-4 py-2">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Administración</h3>
                    </div>
                    
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                        <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                        <i class="fas fa-tags w-5 h-5 mr-3"></i>
                        Categorías
                    </a>
                    
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                        <i class="fas fa-box w-5 h-5 mr-3"></i>
                        Productos
                    </a>
                    
                    <a href="{{ route('admin.customization-options.index') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200 {{ request()->routeIs('admin.customization-options.*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                        <i class="fas fa-cog w-5 h-5 mr-3"></i>
                        Personalización
                    </a>
                    
                    <a href="{{ route('admin.promotions.index') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200 {{ request()->routeIs('admin.promotions.*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                        <i class="fas fa-percentage w-5 h-5 mr-3"></i>
                        Promociones
                    </a>
                    
                    <a href="{{ route('admin.combos.index') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200 {{ request()->routeIs('admin.combos.*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                        <i class="fas fa-box-open w-5 h-5 mr-3"></i>
                        Combos
                    </a>
                    
                    <a href="{{ route('admin.reports.index') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                        <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
                        Reportes
                    </a>
                @else
                    <!-- Menú Cajero -->
                    <div class="px-4 py-2">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Punto de Venta</h3>
                    </div>
                    
                    <a href="{{ route('cashier.dashboard') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200 {{ request()->routeIs('cashier.dashboard') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                        <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('cashier.sale.index') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200 {{ request()->routeIs('cashier.sale.index') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                        <i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>
                        Nueva Venta
                    </a>
                    
                    <a href="{{ route('cashier.sale.history') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200 {{ request()->routeIs('cashier.sale.history') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                        <i class="fas fa-history w-5 h-5 mr-3"></i>
                        Historial de Ventas
                    </a>
                    
                    <a href="{{ route('cashier.cash-cut.index') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-200 {{ request()->routeIs('cashier.cash-cut.*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : '' }}">
                        <i class="fas fa-calculator w-5 h-5 mr-3"></i>
                        Corte de Caja
                    </a>
                @endif
            </nav>
            
            <!-- Usuario y Logout -->
            <div class="absolute bottom-0 w-64 p-4 bg-gray-50 border-t">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-red-600 transition duration-200" title="Cerrar Sesión">
                            <i class="fas fa-sign-out-alt"></i>
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
            <div class="bg-white shadow-sm border-b">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                            @hasSection('breadcrumb')
                                <nav class="text-sm text-gray-600 mt-1">
                                    @yield('breadcrumb')
                                </nav>
                            @endif
                        </div>
                        
                        @hasSection('header-actions')
                            <div class="flex space-x-2">
                                @yield('header-actions')
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endauth

            <!-- Alerts -->
            @if(session('success'))
                <div class="mx-6 mt-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded fade-in" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-6 mt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded fade-in" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="mx-6 mt-4">
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded fade-in" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span>{{ session('warning') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main class="@auth p-6 @else p-0 @endauth">
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
    </script>
    
    @stack('scripts')
</body>
</html>
