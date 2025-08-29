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
        
        /* Sidebar moderno estilo FoodMeal con colores TOCHIS */
        .modern-sidebar {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            width: 280px;
            border-radius: 0 24px 24px 0;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            z-index: 1000;
            overflow: hidden;
            box-shadow: 4px 0 24px rgba(249, 115, 22, 0.15);
        }
        
        .sidebar-content {
            padding: 32px 24px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .logo-section {
            margin-bottom: 40px;
        }
        
        .logo-section h1 {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            letter-spacing: 1px;
        }
        
        .logo-section h1 i {
            margin-right: 12px;
            font-size: 32px;
        }
        
        .logo-section p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            font-weight: 500;
        }
        
        .nav-section {
            flex: 1;
            overflow-y: auto;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            margin-bottom: 8px;
            border-radius: 16px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .nav-item:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateX(4px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .nav-item.active {
            background: rgba(255, 255, 255, 0.95);
            color: #ea580c;
            font-weight: 600;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        }
        
        .nav-item i {
            width: 24px;
            height: 24px;
            margin-right: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        
        .nav-item span {
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .user-section {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 20px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
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
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .user-details h4 {
            color: white;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 2px;
        }
        
        .user-details p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
            font-weight: 500;
        }
        
        .logout-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.8);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: scale(1.05);
        }
        
        /* Contenido principal */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            background: #f8fafc;
        }
        
        /* Scrollbar personalizado */
        .nav-section::-webkit-scrollbar {
            width: 6px;
        }
        
        .nav-section::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        
        .nav-section::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }
        
        .nav-section::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex">
        @auth
        <!-- Sidebar Moderno -->
        <div class="modern-sidebar">
            <div class="sidebar-content">
                <!-- Logo Section -->
                <div class="logo-section">
                    <h1>
                        <i class="fas fa-utensils"></i>
                        TOCHIS
                    </h1>
                    <p>Sistema de Punto de Venta</p>
                </div>
                
                <!-- Navigation -->
                <div class="nav-section">
                    @if(auth()->user()->isAdmin())
                        <!-- Menú Administrador -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Dashboard</span>
                        </a>
                        
                        <a href="{{ route('admin.categories.index') }}" 
                           class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="fas fa-tags"></i>
                            <span>Categorías</span>
                        </a>
                        
                        <a href="{{ route('admin.products.index') }}" 
                           class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="fas fa-hamburger"></i>
                            <span>Productos</span>
                        </a>
                        
                        <a href="{{ route('admin.customization-options.index') }}" 
                           class="nav-item {{ request()->routeIs('admin.customization-options.*') ? 'active' : '' }}">
                            <i class="fas fa-cogs"></i>
                            <span>Personalización</span>
                        </a>
                        
                        <a href="{{ route('admin.promotions.index') }}" 
                           class="nav-item {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                            <i class="fas fa-fire"></i>
                            <span>Promociones</span>
                        </a>
                        
                        <a href="{{ route('admin.combos.index') }}" 
                           class="nav-item {{ request()->routeIs('admin.combos.*') ? 'active' : '' }}">
                            <i class="fas fa-box-open"></i>
                            <span>Combos</span>
                        </a>
                        
                        <a href="{{ route('admin.reports.index') }}" 
                           class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Reportes</span>
                        </a>
                    @else
                        <!-- Menú Cajero -->
                        <a href="{{ route('cashier.dashboard') }}" 
                           class="nav-item {{ request()->routeIs('cashier.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Dashboard</span>
                        </a>
                        
                        <a href="{{ route('cashier.sale.index') }}" 
                           class="nav-item {{ request()->routeIs('cashier.sale.index') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Nueva Venta</span>
                        </a>
                        
                        <a href="{{ route('cashier.sale.history') }}" 
                           class="nav-item {{ request()->routeIs('cashier.sale.history') ? 'active' : '' }}">
                            <i class="fas fa-receipt"></i>
                            <span>Historial de Ventas</span>
                        </a>
                        
                        <a href="{{ route('cashier.cash-cut.index') }}" 
                           class="nav-item {{ request()->routeIs('cashier.cash-cut.*') ? 'active' : '' }}">
                            <i class="fas fa-calculator"></i>
                            <span>Corte de Caja</span>
                        </a>
                    @endif
                </div>
                
                <!-- User Section -->
                <div class="user-section">
                    <div class="user-info">
                        <div class="user-avatar">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="user-details">
                            <h4>{{ auth()->user()->name }}</h4>
                            <p>{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="logout-btn" title="Cerrar Sesión">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
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
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-sm mx-8 mt-6 fade-in">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm mx-8 mt-6 fade-in">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm mx-8 mt-6 fade-in">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium">Se encontraron errores:</h3>
                            <ul class="mt-2 text-sm list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main class="px-8 py-8">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.12.0/cdn.js" defer></script>
    @stack('scripts')
</body>
</html>
