<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CustomizationOptionsController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\PrinterController;
use App\Http\Controllers\Admin\MercadoPagoController;
use App\Http\Controllers\Cashier\DashboardController as CashierDashboardController;
use App\Http\Controllers\Cashier\SaleController;
use App\Http\Controllers\Cashier\CashCutController;
use App\Http\Controllers\Cashier\MercadoPagoPaymentController;

// Rutas públicas
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Webhook de MercadoPago (sin autenticación)
Route::post('/webhook/mercadopago', [MercadoPagoController::class, 'webhook'])->name('webhook.mercadopago');

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
        Route::resource('combos', ComboController::class);
        Route::patch('/combos/{combo}/toggle-status', [ComboController::class, 'toggleStatus'])->name('combos.toggle-status');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
        Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/cash-cuts', [ReportController::class, 'cashCuts'])->name('reports.cash-cuts');
        
        // Rutas para impresoras térmicas
        Route::resource('printers', PrinterController::class);
        Route::post('/printers/{printer}/activate', [PrinterController::class, 'activate'])->name('printers.activate');
        Route::post('/printers/{printer}/test', [PrinterController::class, 'test'])->name('printers.test');
        Route::post('/printers/{printer}/print-test', [PrinterController::class, 'printTest'])->name('printers.print-test');
        Route::get('/printers/{printer}/config', [PrinterController::class, 'getConfig'])->name('printers.config');
        
        // Rutas para MercadoPago
        Route::resource('mercadopago', MercadoPagoController::class);
        Route::post('/mercadopago/{mercadopago}/activate', [MercadoPagoController::class, 'activate'])->name('mercadopago.activate');
        Route::post('/mercadopago/{mercadopago}/test', [MercadoPagoController::class, 'test'])->name('mercadopago.test');
        Route::post('/mercadopago/{mercadopago}/test-qr', [MercadoPagoController::class, 'generateTestQR'])->name('mercadopago.test-qr');
        Route::post('/mercadopago/{mercadopago}/test-payment', [MercadoPagoController::class, 'createTestPayment'])->name('mercadopago.test-payment');
        Route::get('/mercadopago/{mercadopago}/config', [MercadoPagoController::class, 'getConfig'])->name('mercadopago.config');
    });

    // Rutas para cajeros
    Route::middleware(['role:cashier'])->prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/dashboard', [CashierDashboardController::class, 'index'])->name('dashboard');
        Route::get('/sale', [SaleController::class, 'index'])->name('sale.index');
        Route::post('/sale', [SaleController::class, 'store'])->name('sale.store');
        Route::get('/sales/history', [SaleController::class, 'history'])->name('sale.history');
        Route::get('/api/promotions', [SaleController::class, 'getAvailablePromotions'])->name('sale.promotions');
        Route::post('/api/combos/suggest', [SaleController::class, 'getSuggestedCombos'])->name('sale.combos.suggest');
        Route::post('/api/combos/apply', [SaleController::class, 'applyCombo'])->name('sale.combos.apply');
        
        // Rutas de MercadoPago para pagos con tarjeta
        Route::prefix('mercadopago')->name('mercadopago.')->group(function () {
            Route::get('/config', [MercadoPagoPaymentController::class, 'getConfig'])->name('config');
            Route::get('/point-devices', [MercadoPagoPaymentController::class, 'getPointDevices'])->name('point-devices');
            Route::post('/process-card-payment', [MercadoPagoPaymentController::class, 'processCardPayment'])->name('process-card-payment');
            Route::post('/check-payment-status', [MercadoPagoPaymentController::class, 'checkPaymentStatus'])->name('check-payment-status');
            Route::post('/cancel-payment', [MercadoPagoPaymentController::class, 'cancelPayment'])->name('cancel-payment');
        });
        
        // RUTA DE PRUEBA TEMPORAL - REMOVER EN PRODUCCIÓN
        Route::get('/api/test-combos', function() {
            try {
                $combos = \App\Models\Combo::active()->where('auto_suggest', true)->with('products')->get();
                return response()->json([
                    'success' => true,
                    'combos_count' => $combos->count(),
                    'combos' => $combos->map(function($combo) {
                        return [
                            'id' => $combo->id,
                            'name' => $combo->name,
                            'products_count' => $combo->products->count(),
                            'is_active' => $combo->is_active,
                            'auto_suggest' => $combo->auto_suggest
                        ];
                    })
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
            }
        })->name('test.combos');
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
