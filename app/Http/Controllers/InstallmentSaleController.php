<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Installment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Log;

class InstallmentSaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Installment::whereNotNull('installment_sale_number')
            ->whereNull('installment_number')
            ->orderBy('created_at', 'desc');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('installment_sale_number', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_mobile', 'LIKE', "%{$search}%");
            });
        }
        
        // Payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('sale_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('sale_date', '<=', $request->date_to);
        }
        
        $installmentSales = $query->paginate(15);
        
        // Calculate statistics
        $stats = [
            'total_count' => Installment::whereNotNull('installment_sale_number')
                ->whereNull('installment_number')->count(),
            'total_sales' => Installment::whereNotNull('installment_sale_number')
                ->whereNull('installment_number')->sum('total_amount'),
            'active_count' => Installment::whereNotNull('installment_sale_number')
                ->whereNull('installment_number')
                ->where('payment_status', 'active')->count(),
            'overdue_count' => 0 // Can be calculated based on due dates
        ];
        
        return view('installments.index', compact('installmentSales', 'stats'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $categories = Category::all();
        $brands = Brand::with('products')->orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        
        return view('installments.create', compact('customers', 'categories', 'brands', 'products'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            // Customer information
            'customer_name' => 'required|string|max:255',
            'customer_mobile' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'customer_nid' => 'nullable|string|max:50',
            'customer_nid_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Customer's spouse information
            'customer_wife_name' => 'nullable|string|max:255',
            'customer_wife_nid' => 'nullable|string|max:50',
            'customer_wife_nid_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Guarantor information
            'guarantor_name' => 'required|string|max:255',
            'guarantor_mobile' => 'required|string|max:20',
            'guarantor_address' => 'required|string|max:500',
            'guarantor_nid' => 'nullable|string|max:50',
            'guarantor_security_info' => 'nullable|string|max:1000',
            'guarantor_security_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Multi-product data
            'products_data' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            
            // Installment payment options
            'down_payment' => 'required|numeric|min:0',
            'total_installments' => 'required|integer|min:1|max:12',
            'sale_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Parse products data
        $productsData = json_decode($validated['products_data'], true);
        
        if (empty($productsData)) {
            return back()->withErrors(['products_data' => 'At least one product is required']);
        }

        // Handle file uploads
        $customerNidImagePath = null;
        $customerWifeNidImagePath = null;
        $guarantorSecurityImagePath = null;

        if ($request->hasFile('customer_nid_image')) {
            $customerNidImagePath = $request->file('customer_nid_image')->store('installments/customer-nids', 'public');
        }

        if ($request->hasFile('customer_wife_nid_image')) {
            $customerWifeNidImagePath = $request->file('customer_wife_nid_image')->store('installments/spouse-nids', 'public');
        }

        if ($request->hasFile('guarantor_security_image')) {
            $guarantorSecurityImagePath = $request->file('guarantor_security_image')->store('installments/guarantor-security', 'public');
        }

        // Generate unique installment sale number
        $lastInstallment = Installment::whereNotNull('installment_sale_number')
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastInstallment && $lastInstallment->installment_sale_number) {
            preg_match('/INS-(\d+)/', $lastInstallment->installment_sale_number, $matches);
            $lastNumber = isset($matches[1]) ? intval($matches[1]) : 0;
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        $installmentSaleNumber = 'INS-' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);

        // Calculate monthly installment
        $remainingAmount = $validated['total_amount'] - $validated['down_payment'];
        $monthlyInstallment = $remainingAmount / $validated['total_installments'];

        // Create main installment sale record
        $installmentSale = Installment::create([
            'installment_sale_number' => $installmentSaleNumber,
            'customer_name' => $validated['customer_name'],
            'customer_mobile' => $validated['customer_mobile'],
            'customer_address' => $validated['customer_address'],
            'customer_nid' => $validated['customer_nid'] ?? null,
            'customer_nid_image' => $customerNidImagePath,
            'customer_wife_name' => $validated['customer_wife_name'] ?? null,
            'customer_wife_nid' => $validated['customer_wife_nid'] ?? null,
            'customer_wife_nid_image' => $customerWifeNidImagePath,
            'guarantor_name' => $validated['guarantor_name'],
            'guarantor_mobile' => $validated['guarantor_mobile'],
            'guarantor_address' => $validated['guarantor_address'],
            'guarantor_nid' => $validated['guarantor_nid'] ?? null,
            'guarantor_security_info' => $validated['guarantor_security_info'] ?? null,
            'guarantor_security_image' => $guarantorSecurityImagePath,
            'products_data' => $productsData,
            'subtotal' => $validated['subtotal'],
            'discount_amount' => $validated['discount_amount'] ?? 0,
            'total_amount' => $validated['total_amount'],
            'down_payment' => $validated['down_payment'],
            'total_installments' => $validated['total_installments'],
            'monthly_installment' => $monthlyInstallment,
            'sale_date' => $validated['sale_date'],
            'payment_status' => 'active',
            'notes' => $validated['notes'] ?? null
        ]);

        // Reduce product stock
        foreach ($productsData as $productData) {
            $product = Product::find($productData['product_id']);
            if ($product) {
                $product->decrement('stock_quantity', $productData['quantity']);
            }
        }

        return redirect()->route('installment-sales.index')
            ->with('success', "Installment sale {$installmentSaleNumber} created successfully!");
    }

    public function show(Installment $installment_sale)
    {
        $installmentSale = $installment_sale;
        
        $installmentPayments = Installment::where('installment_sale_number', $installmentSale->installment_sale_number)
            ->whereNotNull('installment_number')
            ->orderBy('installment_number')
            ->get();
        
        return view('installments.show', compact('installmentSale', 'installmentPayments'));
    }

    public function edit(Installment $installment_sale)
    {
        $installmentSale = $installment_sale;
        
        $customers = Customer::orderBy('name')->get();
        $categories = Category::all();
        $brands = Brand::with('products')->orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        
        return view('installments.edit', compact('installmentSale', 'customers', 'categories', 'brands', 'products'));
    }

    public function update(Request $request, Installment $installment_sale)
    {
        $installmentSale = $installment_sale;
        
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_mobile' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'customer_nid' => 'nullable|string|max:50',
            'guarantor_name' => 'required|string|max:255',
            'guarantor_mobile' => 'required|string|max:20',
            'guarantor_address' => 'required|string|max:500',
            'guarantor_nid' => 'nullable|string|max:50',
            'discount_amount' => 'nullable|numeric|min:0',
            'down_payment' => 'required|numeric|min:0',
            'total_installments' => 'required|integer|min:1|max:12',
            'payment_status' => 'required|in:active,completed,defaulted,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        // Recalculate monthly installment
        $remainingAmount = $installmentSale->total_amount - $validated['down_payment'];
        $monthlyInstallment = $remainingAmount / $validated['total_installments'];
        
        $installmentSale->update([
            'customer_name' => $validated['customer_name'],
            'customer_mobile' => $validated['customer_mobile'],
            'customer_address' => $validated['customer_address'],
            'customer_nid' => $validated['customer_nid'] ?? null,
            'guarantor_name' => $validated['guarantor_name'],
            'guarantor_mobile' => $validated['guarantor_mobile'],
            'guarantor_address' => $validated['guarantor_address'],
            'guarantor_nid' => $validated['guarantor_nid'] ?? null,
            'discount_amount' => $validated['discount_amount'] ?? 0,
            'down_payment' => $validated['down_payment'],
            'total_installments' => $validated['total_installments'],
            'monthly_installment' => $monthlyInstallment,
            'payment_status' => $validated['payment_status'],
            'notes' => $validated['notes'] ?? null
        ]);
        
        return redirect()->route('installment-sales.index')
            ->with('success', 'Installment sale updated successfully!');
    }

    public function destroy(Installment $installment_sale)
    {
        $installmentSale = $installment_sale;
        
        // Delete all related installment payments
        Installment::where('installment_sale_number', $installmentSale->installment_sale_number)->delete();
        
        return redirect()->route('installment-sales.index')
            ->with('success', 'Installment sale deleted successfully!');
    }
    
    public function invoice(Installment $installment_sale)
    {
        $installmentSale = $installment_sale;
        
        // Generate installment schedule
        $startDate = Carbon::parse($installmentSale->sale_date);
        $installmentSchedule = [];
        
        for ($i = 1; $i <= $installmentSale->total_installments; $i++) {
            $dueDate = $startDate->copy()->addMonths($i);
            $installmentSchedule[] = [
                'number' => $i,
                'due_date' => $dueDate,
                'amount' => $installmentSale->monthly_installment,
                'status' => 'pending'
            ];
        }
        
        return view('installments.invoice', compact('installmentSale', 'installmentSchedule'));
    }
    
    public function downloadInvoice(Installment $installment_sale)
    {
        try {
            // Set reasonable limits for PDF generation
            ini_set('max_execution_time', 60);
            ini_set('memory_limit', '256M');
            
            $installmentSale = $installment_sale;
            
            // Convert images to Base64 for PDF generation
            $logoBase64 = null;
            $logoPath = public_path('images/me_logo2.png');
            
            if (file_exists($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $logoMimeType = mime_content_type($logoPath) ?: 'image/png';
                $logoBase64 = 'data:' . $logoMimeType . ';base64,' . base64_encode($logoData);
            }
            
            // Generate installment schedule
            $startDate = Carbon::parse($installmentSale->sale_date);
            $installmentSchedule = [];
            
            for ($i = 1; $i <= $installmentSale->total_installments; $i++) {
                $dueDate = $startDate->copy()->addMonths($i);
                $installmentSchedule[] = [
                    'number' => $i,
                    'due_date' => $dueDate,
                    'amount' => $installmentSale->monthly_installment,
                    'status' => 'pending'
                ];
            }
            
            // Use optimized PDF template
            $pdf = PDF::loadView('installments.invoice-pdf-optimized', compact('installmentSale', 'installmentSchedule', 'logoBase64'))
                     ->setPaper('A4', 'portrait')
                     ->setOptions([
                         'isRemoteEnabled' => true,
                         'isJavascriptEnabled' => false,
                         'isPhpEnabled' => false,
                         'dpi' => 96,
                         'chroot' => public_path(),
                     ]);
            
            return $pdf->download('installment-invoice-' . $installmentSale->installment_sale_number . '.pdf');
            
        } catch (\Exception $e) {
            // Log the error and provide fallback
            Log::error('Installment PDF generation error: ' . $e->getMessage());
            
            // Try with simple template as fallback
            try {
                // Convert logo to Base64 for fallback template as well
                $logoBase64 = null;
                $logoPath = public_path('images/me_logo2.png');
                
                if (file_exists($logoPath)) {
                    $logoData = file_get_contents($logoPath);
                    $logoMimeType = mime_content_type($logoPath) ?: 'image/png';
                    $logoBase64 = 'data:' . $logoMimeType . ';base64,' . base64_encode($logoData);
                }
                
                $pdf = PDF::loadView('installments.invoice', compact('installmentSale', 'installmentSchedule', 'logoBase64'))
                         ->setPaper('A4', 'portrait');
                return $pdf->download('installment-invoice-' . $installmentSale->installment_sale_number . '.pdf');
            } catch (\Exception $e2) {
                Log::error('Installment PDF fallback failed: ' . $e2->getMessage());
                return redirect()->back()->with('error', 'Unable to generate PDF. Please try again or contact support.');
            }
        }
    }
}
