<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CustomizationOptionsController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Cashier\DashboardController as CashierDashboardController;
use App\Http\Controllers\Cashier\SaleController;
use App\Http\Controllers\Cashier\CashCutController;

// Rutas públicas
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Rutas para administradores
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class);
        Route::get('/categories/{category}/customization-options', [CategoryController::class, 'customizationOptions'])->name('categories.customization-options');
        Route::post('/categories/{category}/customization-options', [CategoryController::class, 'updateCustomizationOptions'])->name('categories.update-customization-options');
        Route::resource('products', ProductController::class);
        Route::resource('customization-options', CustomizationOptionsController::class);
        Route::resource('promotions', PromotionController::class);
        Route::patch('/promotions/{promotion}/toggle-status', [PromotionController::class, 'toggleStatus'])->name('promotions.toggle-status');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/cash-cuts', [ReportController::class, 'cashCuts'])->name('reports.cash-cuts');
    });

    // Rutas para cajeros
    Route::middleware(['role:cashier'])->prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/dashboard', [CashierDashboardController::class, 'index'])->name('dashboard');
        Route::get('/sale', [SaleController::class, 'index'])->name('sale.index');
        Route::post('/sale', [SaleController::class, 'store'])->name('sale.store');
        Route::get('/sales/history', [SaleController::class, 'history'])->name('sale.history');
        Route::get('/api/promotions', [SaleController::class, 'getAvailablePromotions'])->name('sale.promotions');
        Route::get('/cash-cut', [CashCutController::class, 'index'])->name('cash-cut.index');
        Route::post('/cash-cut/open', [CashCutController::class, 'open'])->name('cash-cut.open');
        Route::post('/cash-cut/close', [CashCutController::class, 'close'])->name('cash-cut.close');
        Route::post('/cash-cut/add-expense', [CashCutController::class, 'addExpense'])->name('cash-cut.add-expense');
        Route::post('/cash-cut/add-income', [CashCutController::class, 'addIncome'])->name('cash-cut.add-income');
    });

    // Ruta de redirección según el rol
    Route::get('/home', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('cashier.dashboard');
        }
    })->name('home');
    
    // Rutas API
    Route::prefix('api')->group(function () {
        Route::get('/products/{id}/options', [\App\Http\Controllers\Api\ProductController::class, 'getOptions']);
        Route::get('/customization-options', [\App\Http\Controllers\Api\CustomizationController::class, 'getOptions']);
    });
});
