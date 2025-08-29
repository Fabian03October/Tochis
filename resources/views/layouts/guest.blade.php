<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'TOCHIS POS')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .login-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .food-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M30 30c0-11.046-8.954-20-20-20s-20 8.954-20 20 8.954 20 20 20 20-8.954 20-20zm15 0c0-11.046-8.954-20-20-20s-20 8.954-20 20 8.954 20 20 20 20-8.954 20-20z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .orange-gradient {
            background: linear-gradient(135deg, #ff8a00 0%, #ff6b35 100%);
        }
        
        .input-focus:focus {
            border-color: #ff8a00;
            box-shadow: 0 0 0 3px rgba(255, 138, 0, 0.1);
        }
        
        .btn-orange {
            background: linear-gradient(135deg, #ff8a00 0%, #ff6b35 100%);
            transition: all 0.3s ease;
        }
        
        .btn-orange:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 138, 0, 0.3);
        }
        
        .social-btn {
            transition: all 0.3s ease;
        }
        
        .social-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen">
        @yield('content')
    </div>
</body>
</html>
