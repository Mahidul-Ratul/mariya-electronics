<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Installment;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Today's statistics
        $todaysSales = Sale::whereDate('sale_date', today())->sum('total_amount');
        $todaysPayments = Payment::whereDate('payment_date', today())->sum('amount');
        
        // This month statistics
        $monthSales = Sale::whereMonth('sale_date', now()->month)
                         ->whereYear('sale_date', now()->year)
                         ->sum('total_amount');
        
        // Product statistics
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock_quantity', '<', 10)->get();
        $lowStockCount = Product::where('stock_quantity', '<', 10)->count();
        $outOfStockProducts = Product::where('stock_quantity', '=', 0)->count();
        
        // Customer statistics
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('is_blacklisted', false)->count();
        
        // Installment statistics
        $pendingInstallments = Installment::where('status', 'pending')->count();
        $overdueInstallments = Installment::where('status', 'overdue')
                                         ->orWhere(function($q) {
                                             $q->where('status', 'pending')
                                               ->where('due_date', '<', now());
                                         })->count();
        
        $dueSoonInstallments = Installment::where('status', 'pending')
                                         ->whereBetween('due_date', [now(), now()->addDays(7)])
                                         ->count();
        
        // Recent activities
        $recentSales = Sale::with(['customer', 'product'])
                          ->orderBy('created_at', 'desc')
                          ->limit(5)
                          ->get();
        
        $recentPayments = Payment::with(['sale.customer'])
                                ->orderBy('payment_date', 'desc')
                                ->limit(5)
                                ->get();
        
        // Overdue installments for reminders
        $overdueInstallmentsList = Installment::with(['sale.customer'])
                                              ->where('status', 'pending')
                                              ->where('due_date', '<', now())
                                              ->orderBy('due_date')
                                              ->limit(10)
                                              ->get();
        
        // Due soon installments
        $dueSoonInstallmentsList = Installment::with(['sale.customer'])
                                             ->where('status', 'pending')
                                             ->whereBetween('due_date', [now(), now()->addDays(7)])
                                             ->orderBy('due_date')
                                             ->limit(10)
                                             ->get();
        
        // Top selling products
        $topProducts = Product::withCount('sales')
                             ->orderBy('sales_count', 'desc')
                             ->limit(5)
                             ->get();

        return view('dashboard', compact(
            'todaysSales', 'todaysPayments', 'monthSales',
            'totalProducts', 'lowStockProducts', 'lowStockCount', 'outOfStockProducts',
            'totalCustomers', 'activeCustomers',
            'pendingInstallments', 'overdueInstallments', 'dueSoonInstallments',
            'recentSales', 'recentPayments', 'overdueInstallmentsList', 
            'dueSoonInstallmentsList', 'topProducts'
        ));
    }
}
