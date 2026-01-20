<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Installment;
use App\Models\Sale;
use App\Models\Payment;
use Carbon\Carbon;

class InstallmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Installment::with(['sale.customer', 'sale.product']);
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by due date
        if ($request->has('due_filter')) {
            switch ($request->due_filter) {
                case 'overdue':
                    $query->where('status', '!=', 'paid')
                          ->where('due_date', '<', now());
                    break;
                case 'due_soon':
                    $query->where('status', 'pending')
                          ->whereBetween('due_date', [now(), now()->addDays(7)]);
                    break;
                case 'this_month':
                    $query->whereMonth('due_date', now()->month)
                          ->whereYear('due_date', now()->year);
                    break;
            }
        }
        
        // Search by customer name
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('sale.customer', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }
        
        $installments = $query->orderBy('due_date')->paginate(15);
        
        // Statistics
        $stats = [
            'total' => Installment::count(),
            'pending' => Installment::where('status', 'pending')->count(),
            'overdue' => Installment::where('status', '!=', 'paid')
                                   ->where('due_date', '<', now())->count(),
            'due_soon' => Installment::where('status', 'pending')
                                    ->whereBetween('due_date', [now(), now()->addDays(7)])
                                    ->count(),
            'total_pending_amount' => Installment::where('status', 'pending')
                                                ->sum('amount'),
        ];
        
        return view('installments.index', compact('installments', 'stats'));
    }

    public function overdue()
    {
        $installments = Installment::with(['sale.customer', 'sale.product'])
                                  ->where('status', '!=', 'paid')
                                  ->where('due_date', '<', now())
                                  ->orderBy('due_date')
                                  ->get();
        
        return view('installments.overdue', compact('installments'));
    }

    public function dueSoon()
    {
        $installments = Installment::with(['sale.customer', 'sale.product'])
                                  ->where('status', 'pending')
                                  ->whereBetween('due_date', [now(), now()->addDays(7)])
                                  ->orderBy('due_date')
                                  ->get();
        
        return view('installments.due-soon', compact('installments'));
    }

    public function pay(Request $request, Installment $installment)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,check,card',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000'
        ]);

        $paymentAmount = $validated['amount'];
        $totalDue = $installment->amount + $installment->penalty_amount - $installment->paid_amount;

        // Create payment record
        $payment = Payment::create([
            'sale_id' => $installment->sale_id,
            'installment_id' => $installment->id,
            'amount' => $paymentAmount,
            'payment_method' => $validated['payment_method'],
            'payment_date' => $validated['payment_date'],
            'reference_number' => $validated['reference_number'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Update installment
        $installment->paid_amount += $paymentAmount;
        $installment->paid_date = $validated['payment_date'];
        
        if ($installment->paid_amount >= $totalDue) {
            $installment->status = 'paid';
        } else {
            $installment->status = 'partial';
        }
        
        $installment->save();

        return redirect()->back()->with('success', 'Payment recorded successfully!');
    }

    public function addPenalty(Request $request, Installment $installment)
    {
        $validated = $request->validate([
            'penalty_amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string|max:500'
        ]);

        $installment->penalty_amount += $validated['penalty_amount'];
        
        if ($installment->status === 'pending') {
            $installment->status = 'overdue';
        }
        
        if ($validated['reason']) {
            $installment->notes = $installment->notes ? 
                $installment->notes . "\n\nPenalty: " . $validated['reason'] : 
                "Penalty: " . $validated['reason'];
        }
        
        $installment->save();

        return redirect()->back()->with('success', 'Penalty added successfully!');
    }

    public function show(Installment $installment)
    {
        $installment->load(['sale.customer', 'sale.product', 'payment']);
        return view('installments.show', compact('installment'));
    }

    public function markOverdue()
    {
        $overdueInstallments = Installment::where('status', 'pending')
                                         ->where('due_date', '<', now())
                                         ->get();

        foreach ($overdueInstallments as $installment) {
            $installment->update(['status' => 'overdue']);
        }

        return redirect()->back()->with('success', count($overdueInstallments) . ' installments marked as overdue.');
    }
}
