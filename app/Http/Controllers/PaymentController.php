<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Sale;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['sale.customer', 'sale.product', 'installment']);
        
        // Date filters
        if ($request->has('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('payment_date', today());
                    break;
                case 'this_week':
                    $query->whereBetween('payment_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('payment_date', now()->month)
                          ->whereYear('payment_date', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('payment_date', now()->subMonth()->month)
                          ->whereYear('payment_date', now()->subMonth()->year);
                    break;
            }
        }
        
        // Custom date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('payment_date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('payment_date', '<=', $request->end_date);
        }
        
        // Payment method filter
        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        // Customer search
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('sale.customer', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }
        
        $payments = $query->orderBy('payment_date', 'desc')->paginate(15);
        
        // Statistics
        $totalToday = Payment::whereDate('payment_date', today())->sum('amount');
        $totalThisMonth = Payment::whereMonth('payment_date', now()->month)
                                ->whereYear('payment_date', now()->year)
                                ->sum('amount');
        
        return view('payments.index', compact('payments', 'totalToday', 'totalThisMonth'));
    }

    public function create(Request $request)
    {
        $saleId = $request->get('sale_id');
        $sale = null;
        
        if ($saleId) {
            $sale = Sale::with(['customer', 'installments'])->find($saleId);
        }
        
        $sales = Sale::with(['customer'])->where('payment_type', 'installment')
                    ->where('status', '!=', 'completed')
                    ->orderBy('sale_date', 'desc')
                    ->get();
        
        return view('payments.create', compact('sales', 'sale'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'installment_id' => 'nullable|exists:installments,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,check,card',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000'
        ]);

        Payment::create($validated);

        return redirect()->back()->with('success', 'Payment recorded successfully!');
    }

    public function show(Payment $payment)
    {
        $payment->load(['sale.customer', 'sale.product', 'installment']);
        return view('payments.show', compact('payment'));
    }
}
