<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_blacklisted', false);
            } elseif ($request->status === 'blacklisted') {
                $query->where('is_blacklisted', true);
            }
        }
        
        $customers = $query->orderBy('name')->paginate(15);
        
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'id_card_type' => 'nullable|string|max:50',
            'id_card_number' => 'nullable|string|max:100',
            'credit_limit' => 'nullable|numeric|min:0',
            'is_blacklisted' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully!');
    }

    public function show(Customer $customer)
    {
        $customer->load(['sales.product', 'installments', 'payments']);
        
        $stats = [
            'total_purchases' => $customer->sales->sum('total_amount'),
            'total_paid' => $customer->payments->sum('amount'),
            'pending_installments' => $customer->installments->where('status', 'pending')->count(),
            'overdue_installments' => $customer->installments->where('status', 'overdue')->count(),
        ];
        
        return view('customers.show', compact('customer', 'stats'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'id_card_type' => 'nullable|string|max:50',
            'id_card_number' => 'nullable|string|max:100',
            'credit_limit' => 'nullable|numeric|min:0',
            'is_blacklisted' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }
}
