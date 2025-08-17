<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\CashCut;
use App\Models\CashMovement;
use App\Models\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CashCutController extends Controller
{
    public function index()
    {
        $currentCashCut = CashCut::where('user_id', auth()->id())
                                 ->where('status', 'open')
                                 ->first();

        $todaySales = null;
        $cashSales = collect();
        $cardSales = collect();
        $transferSales = collect();
        $totalSales = 0;
        $totalAmount = 0;
        $cardAmount = 0;
        $transferAmount = 0;
        $cashMovements = collect();
        $totalExpenses = 0;
        $totalIncome = 0;

        if ($currentCashCut) {
            $todaySales = Sale::where('user_id', auth()->id())
                             ->where('created_at', '>=', $currentCashCut->opened_at)
                             ->where('status', 'completed')
                             ->with('saleDetails')
                             ->orderBy('created_at', 'desc')
                             ->get();

            $totalSales = $todaySales->count();
            
            // Solo contar ventas en efectivo para el corte de caja físico
            $cashSales = $todaySales->where('payment_method', 'cash');
            $cardSales = $todaySales->where('payment_method', 'card');
            $transferSales = $todaySales->where('payment_method', 'transfer');
            
            $totalAmount = $cashSales->sum('total');
            $cardAmount = $cardSales->sum('total');
            $transferAmount = $transferSales->sum('total');

            // Obtener movimientos de caja
            $cashMovements = CashMovement::where('cash_cut_id', $currentCashCut->id)
                                       ->orderBy('created_at', 'desc')
                                       ->get();

            $totalExpenses = $cashMovements->where('type', 'expense')->sum('amount');
            $totalIncome = $cashMovements->where('type', 'income')->sum('amount');

            // Actualizar el corte de caja con las ventas actuales (solo efectivo)
            $currentCashCut->update([
                'sales_amount' => $totalAmount,
                'total_sales' => $totalSales,
            ]);
        }

        $recentCashCuts = CashCut::where('user_id', auth()->id())
                                 ->where('status', 'closed')
                                 ->orderBy('closed_at', 'desc')
                                 ->limit(5)
                                 ->get();

        return view('cashier.cash-cut.index', compact(
            'currentCashCut',
            'todaySales',
            'totalSales',
            'totalAmount',
            'recentCashCuts',
            'cashMovements',
            'totalExpenses',
            'totalIncome',
            'cashSales',
            'cardSales',
            'transferSales',
            'cardAmount',
            'transferAmount'
        ));
    }

    public function open(Request $request)
    {
        $request->validate([
            'initial_amount' => 'required|numeric|min:0',
        ]);

        // Verificar que no haya un corte abierto
        $existingCashCut = CashCut::where('user_id', auth()->id())
                                  ->where('status', 'open')
                                  ->first();

        if ($existingCashCut) {
            return redirect()->route('cashier.cash-cut.index')
                           ->with('error', 'Ya tienes un corte de caja abierto.');
        }

        CashCut::create([
            'user_id' => auth()->id(),
            'initial_amount' => $request->initial_amount,
            'opened_at' => now(),
            'status' => 'open',
        ]);

        return redirect()->route('cashier.cash-cut.index')
                        ->with('success', 'Corte de caja abierto exitosamente.');
    }

    public function close(Request $request)
    {
        $request->validate([
            'final_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $currentCashCut = CashCut::where('user_id', auth()->id())
                                 ->where('status', 'open')
                                 ->first();

        if (!$currentCashCut) {
            return redirect()->route('cashier.cash-cut.index')
                           ->with('error', 'No tienes un corte de caja abierto.');
        }

        // Calcular ventas desde la apertura
        $salesAmount = Sale::where('user_id', auth()->id())
                          ->where('created_at', '>=', $currentCashCut->opened_at)
                          ->where('status', 'completed')
                          ->sum('total');

        $totalSales = Sale::where('user_id', auth()->id())
                         ->where('created_at', '>=', $currentCashCut->opened_at)
                         ->where('status', 'completed')
                         ->count();

        // Calcular movimientos de caja
        $totalExpenses = CashMovement::where('cash_cut_id', $currentCashCut->id)
                                    ->where('type', 'expense')
                                    ->sum('amount');

        $totalIncome = CashMovement::where('cash_cut_id', $currentCashCut->id)
                                  ->where('type', 'income')
                                  ->sum('amount');

        // Calcular monto esperado considerando movimientos
        $expectedAmount = $currentCashCut->initial_amount + $salesAmount + $totalIncome - $totalExpenses;

        // Actualizar y cerrar el corte
        $currentCashCut->update([
            'sales_amount' => $salesAmount,
            'total_sales' => $totalSales,
            'final_amount' => $request->final_amount,
            'expected_amount' => $expectedAmount,
            'difference' => $request->final_amount - $expectedAmount,
            'notes' => $request->notes,
            'closed_at' => now(),
            'status' => 'closed',
        ]);

        return redirect()->route('cashier.cash-cut.index')
                        ->with('success', 'Corte de caja cerrado exitosamente.');
    }

    /**
     * Add expense to current cash cut
     */
    public function addExpense(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'concept' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $currentCashCut = CashCut::where('user_id', auth()->id())
                                 ->where('status', 'open')
                                 ->first();

        if (!$currentCashCut) {
            return redirect()->route('cashier.cash-cut.index')
                           ->with('error', 'No tienes un corte de caja abierto.');
        }

        CashMovement::create([
            'cash_cut_id' => $currentCashCut->id,
            'user_id' => auth()->id(),
            'type' => 'expense',
            'amount' => $request->amount,
            'concept' => $request->concept,
            'description' => $request->description,
        ]);

        return redirect()->route('cashier.cash-cut.index')
                        ->with('success', 'Gasto registrado exitosamente.');
    }

    /**
     * Add income to current cash cut
     */
    public function addIncome(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'concept' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $currentCashCut = CashCut::where('user_id', auth()->id())
                                 ->where('status', 'open')
                                 ->first();

        if (!$currentCashCut) {
            return redirect()->route('cashier.cash-cut.index')
                           ->with('error', 'No tienes un corte de caja abierto.');
        }

        CashMovement::create([
            'cash_cut_id' => $currentCashCut->id,
            'user_id' => auth()->id(),
            'type' => 'income',
            'amount' => $request->amount,
            'concept' => $request->concept,
            'description' => $request->description,
        ]);

        return redirect()->route('cashier.cash-cut.index')
                        ->with('success', 'Ingreso registrado exitosamente.');
    }
}
