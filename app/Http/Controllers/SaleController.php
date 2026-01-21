<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Installment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'product']);
        
        // Enhanced search - invoice number, customer name, mobile
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('sale_number', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_mobile', 'LIKE', "%{$search}%")
                  ->orWhereHas('customer', function($subQ) use ($search) {
                      $subQ->where('name', 'LIKE', "%{$search}%")
                           ->orWhere('phone', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Date filters
        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('sale_date', today());
                    break;
                case 'this_week':
                    $query->whereBetween('sale_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('sale_date', now()->month)
                          ->whereYear('sale_date', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('sale_date', now()->subMonth()->month)
                          ->whereYear('sale_date', now()->subMonth()->year);
                    break;
                case 'custom':
                    if ($request->filled('date_from')) {
                        $query->whereDate('sale_date', '>=', $request->date_from);
                    }
                    if ($request->filled('date_to')) {
                        $query->whereDate('sale_date', '<=', $request->date_to);
                    }
                    break;
            }
        }
        
        // Payment type filter
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $sales = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistics
        $stats = [
            'total_sales' => Sale::sum('total_amount'),
            'today_sales' => Sale::whereDate('sale_date', today())->sum('total_amount'),
            'cash_sales' => Sale::whereIn('payment_type', ['cash', 'bkash', 'nagad', 'bank'])->sum('total_amount'),
            'installment_sales' => Sale::where('payment_type', 'installment')->sum('total_amount'),
        ];
        
        return view('sales.index', compact('sales', 'stats'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->where('stock_quantity', '>', 0)->get();
        $customers = Customer::where('is_blacklisted', false)->get();
        $categories = Category::all();
        $brands = Brand::all();
        
        return view('sales.create', compact('products', 'customers', 'categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Customer information (optional fields for new invoice system)
            'customer_name' => 'nullable|string|max:255',
            'customer_address' => 'nullable|string|max:500',
            'customer_mobile' => 'nullable|string|max:20',
            
            // Guarantor information
            'guarantor_name' => 'nullable|string|max:255',
            'guarantor_mobile' => 'nullable|string|max:20',
            
            // Multi-product data
            'products_data' => 'required|string', // JSON string of products
            'subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            
            // Payment options
            'payment_type' => 'required|in:regular,installment',
            'paid_amount' => 'nullable|numeric|min:0', // For installments
            'installment_months' => 'nullable|integer|min:1|max:24',
        ]);

        // Parse products data
        $productsData = json_decode($validated['products_data'], true);
        
        if (empty($productsData)) {
            return back()->withErrors(['products_data' => 'At least one product is required']);
        }

        // Validate stock for all products
        foreach ($productsData as $productData) {
            $product = Product::find($productData['id']);
            if (!$product) {
                return back()->withErrors(['products_data' => 'Invalid product selected']);
            }
            // Note: In real system, you'd want to lookup product by name and check stock
        }

        // Create sale record
        $sale = Sale::create([
            'customer_name' => $validated['customer_name'],
            'customer_address' => $validated['customer_address'],
            'customer_mobile' => $validated['customer_mobile'],
            'guarantor_name' => $validated['guarantor_name'],
            'guarantor_mobile' => $validated['guarantor_mobile'],
            'products_data' => $productsData, // Will be cast to JSON automatically
            'subtotal' => $validated['subtotal'],
            'discount_amount' => $validated['discount_amount'] ?? 0,
            'total_amount' => $validated['total_amount'],
            'paid_amount' => $validated['paid_amount'] ?? 0,
            'payment_type' => $validated['payment_type'],
            'installment_months' => $validated['installment_months'],
            'sale_date' => now(),
            'status' => 'completed'
        ]);

        // Create installments if payment type is installment
        if ($validated['payment_type'] === 'installment' && $validated['installment_months']) {
            $remainingAmount = $validated['total_amount'] - ($validated['paid_amount'] ?? 0);
            $installmentAmount = $remainingAmount / $validated['installment_months'];
            $startDate = now();
            
            for ($i = 1; $i <= $validated['installment_months']; $i++) {
                Installment::create([
                    'sale_id' => $sale->id,
                    'installment_number' => $i,
                    'amount' => $installmentAmount,
                    'due_date' => $startDate->copy()->addMonths($i),
                    'status' => 'pending',
                    'fine_amount' => 0 // Will be calculated when overdue
                ]);
            }
        }

        return redirect()->route('sales.show', $sale)
                        ->with('success', 'Invoice created successfully!');
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'product', 'installments', 'payments']);
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $products = Product::with(['category', 'brand'])->where('is_active', true)->get();
        $customers = Customer::where('is_blacklisted', false)->get();
        $categories = Category::all();
        
        // Get brands with their associated category IDs from products
        $brands = Brand::with('products')->orderBy('name')->get()->map(function($brand) {
            $brand->category_ids = $brand->products->pluck('category_id')->unique()->filter()->values();
            return $brand;
        });
        
        return view('sales.edit', compact('sale', 'products', 'customers', 'categories', 'brands'));
    }

    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            // Customer information
            'customer_name' => 'nullable|string|max:255',
            'customer_address' => 'nullable|string|max:500',
            'customer_mobile' => 'nullable|string|max:20',
            
            // Guarantor information
            'guarantor_name' => 'nullable|string|max:255',
            'guarantor_mobile' => 'nullable|string|max:20',
            
            // Multi-product data
            'products_data' => 'required|string', // JSON string of products
            'subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            
            // Payment options
            'payment_type' => 'nullable|in:regular,installment,cash,bkash,nagad,bank',
            'paid_amount' => 'nullable|numeric|min:0',
            'installment_months' => 'nullable|integer|min:1|max:24',
            'notes' => 'nullable|string|max:1000',
            'status' => 'nullable|in:pending,completed,cancelled,due',
        ]);

        // Parse products data
        $productsData = json_decode($validated['products_data'], true);
        
        if (empty($productsData)) {
            return back()->withErrors(['products_data' => 'At least one product is required']);
        }

        // Update sale record
        $sale->update([
            'customer_name' => $validated['customer_name'] ?? null,
            'customer_address' => $validated['customer_address'] ?? null,
            'customer_mobile' => $validated['customer_mobile'] ?? null,
            'guarantor_name' => $validated['guarantor_name'] ?? null,
            'guarantor_mobile' => $validated['guarantor_mobile'] ?? null,
            'products_data' => $productsData,
            'subtotal' => $validated['subtotal'],
            'discount_amount' => $validated['discount_amount'] ?? 0,
            'total_amount' => $validated['total_amount'],
            'paid_amount' => $validated['paid_amount'] ?? 0,
            'payment_type' => $validated['payment_type'] ?? 'cash',
            'installment_months' => $validated['installment_months'] ?? null,
            'status' => $validated['status'] ?? 'completed',
            'notes' => $validated['notes'] ?? null
        ]);

        // Update installments if payment type is installment
        if ($validated['payment_type'] === 'installment' && $validated['installment_months']) {
            // Delete existing installments
            $sale->installments()->delete();
            
            $remainingAmount = $validated['total_amount'] - ($validated['paid_amount'] ?? 0);
            $installmentAmount = $remainingAmount / $validated['installment_months'];
            $startDate = now();
            
            for ($i = 1; $i <= $validated['installment_months']; $i++) {
                Installment::create([
                    'sale_id' => $sale->id,
                    'installment_number' => $i,
                    'amount' => $installmentAmount,
                    'due_date' => $startDate->copy()->addMonths($i),
                    'status' => 'pending',
                    'fine_amount' => 0
                ]);
            }
        }

        return redirect()->route('sales.show', $sale)
                        ->with('success', 'Sale updated successfully!');
    }

    public function destroy(Sale $sale)
    {
        // Restore stock
        $sale->product->increment('stock_quantity', $sale->quantity);
        
        // Delete installments and payments
        $sale->installments()->delete();
        $sale->payments()->delete();
        
        // Delete sale
        $sale->delete();

        return redirect()->route('sales.index')
                        ->with('success', 'Sale deleted successfully!');
    }

    public function receipt(Sale $sale)
    {
        $sale->load(['customer', 'product', 'installments', 'payments']);
        return view('sales.receipt', compact('sale'));
    }

    public function print(Sale $sale)
    {
        $sale->load(['customer', 'product', 'installments', 'payments']);
        
        $pdf = PDF::loadView('sales.receipt', compact('sale'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('receipt-' . $sale->sale_number . '.pdf');
    }

    public function chooseType()
    {
        return view('sales.choose-type');
    }

    public function createDirectSale()
    {
        $customers = Customer::orderBy('name')->get();
        $categories = Category::all();
        
        // Get brands with their associated category IDs from products
        $brands = Brand::with('products')->orderBy('name')->get()->map(function($brand) {
            $brand->category_ids = $brand->products->pluck('category_id')->unique()->filter()->values();
            return $brand;
        });
        
        $products = Product::with(['category', 'brand'])->orderBy('name')->get();
        
        return view('sales.create-direct', compact('customers', 'categories', 'brands', 'products'));
    }

    public function storeDirectSale(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'products_data' => 'required|json',
            'discount_amount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_type' => 'required|in:cash,bkash,nagad,bank',
            'status' => 'required|in:completed,due',
            'sale_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        // Parse products data
        $products = json_decode($validated['products_data'], true);
        
        if (empty($products)) {
            return back()->withErrors(['products_data' => 'Please add at least one product'])->withInput();
        }
        
        // Calculate totals from products
        $subtotal = 0;
        foreach ($products as $product) {
            $subtotal += $product['quantity'] * $product['unit_price'];
        }
        
        $discount = $validated['discount_amount'] ?? 0;
        $total_amount = $subtotal - $discount;
        $due_amount = $total_amount - $validated['paid_amount'];

        // Generate sale number - get the last sale number to avoid duplicates
        $lastSale = Sale::whereYear('created_at', date('Y'))
                        ->orderBy('id', 'desc')
                        ->first();
        
        if ($lastSale && preg_match('/SAL-\d{4}-(\d{6})/', $lastSale->sale_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }
        
        $saleNumber = 'SAL-' . date('Y') . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        $sale = Sale::create([
            'sale_number' => $saleNumber,
            'customer_id' => null, // For direct sales, we store customer info directly
            'customer_name' => $validated['customer_name'],
            'customer_mobile' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'],
            'product_id' => null, // Multi-product sale - no single product_id
            'products_data' => $validated['products_data'], // Store as JSON string
            'quantity' => null, // Deprecated for multi-product, calculate from products_data if needed
            'unit_price' => null, // Deprecated for multi-product, calculate from products_data if needed
            'discount_amount' => $discount,
            'subtotal' => $subtotal,
            'total_amount' => $total_amount,
            'paid_amount' => $validated['paid_amount'],
            'payment_type' => $validated['payment_type'],
            'status' => $validated['status'],
            'sale_date' => $validated['sale_date'],
            'notes' => $validated['notes']
        ]);

        // Update stock for all products
        foreach ($products as $product) {
            if (isset($product['product_id']) && $product['product_id']) {
                $productModel = Product::find($product['product_id']);
                if ($productModel) {
                    $productModel->decrement('stock_quantity', $product['quantity']);
                }
            }
        }

        return redirect()->route('sales.invoice', $sale)->with('success', 'Direct sale completed successfully!');
    }

    // API methods for product filtering
    public function getProductsByBrand(Brand $brand, Request $request)
    {
        $query = Product::where('brand_id', $brand->id);
        
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        $models = $query->pluck('model')
                        ->unique()
                        ->filter()
                        ->values();
        
        return response()->json($models);
    }

    public function getProductsByBrandModel($brandId, $model, Request $request)
    {
        $query = Product::where('brand_id', $brandId)
                        ->where('model', $model)
                        ->where('stock_quantity', '>', 0);
        
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        $products = $query->with('brand:id,name')
                          ->select('id', 'name', 'model', 'brand_id', 'selling_price', 'stock_quantity')
                          ->get();
        
        return response()->json($products);
    }

    public function getProductsByBrandModelQuery(Request $request)
    {
        $query = Product::where('brand_id', $request->brand_id)
                        ->where('model', $request->model)
                        ->where('stock_quantity', '>', 0);
        
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        $products = $query->with('brand:id,name')
                          ->select('id', 'name', 'model', 'brand_id', 'selling_price', 'stock_quantity')
                          ->get();
        
        return response()->json($products);
    }

    public function getProductDetails(Product $product)
    {
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'brand' => $product->brand,
            'model' => $product->model,
            'selling_price' => $product->selling_price,
            'stock_quantity' => $product->stock_quantity
        ]);
    }

    public function invoice(Sale $sale)
    {
        // Load relationships for the invoice
        $sale->load(['customer', 'product']);
        
        return view('sales.invoice', compact('sale'));
    }

    public function downloadInvoice(Sale $sale)
    {
        try {
            // Set reasonable limits for PDF generation
            ini_set('max_execution_time', 60);
            ini_set('memory_limit', '256M');
            
            $sale->load(['customer', 'product']);
            
            // Convert images to Base64 for PDF generation
            $logoBase64 = null;
            $logoPath = public_path('images/me_logo2.png');
            
            if (file_exists($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $logoMimeType = mime_content_type($logoPath) ?: 'image/png';
                $logoBase64 = 'data:' . $logoMimeType . ';base64,' . base64_encode($logoData);
            }
            
            // Use optimized PDF template
            $pdf = PDF::loadView('sales.invoice-pdf-optimized', compact('sale', 'logoBase64'))
                     ->setPaper('A4', 'portrait')
                     ->setOptions([
                         'isRemoteEnabled' => true,
                         'isJavascriptEnabled' => false,
                         'isPhpEnabled' => false,
                         'dpi' => 96,
                         'chroot' => public_path(),
                     ]);
            
            return $pdf->download('invoice-' . $sale->sale_number . '.pdf');
            
        } catch (\Exception $e) {
            // Use error_log for production console visibility
            error_log('Sales PDF Generation Failed: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine());
            
            // Try with minimal template as final fallback
            try {
                // Convert logo to Base64 for fallback template as well
                $logoBase64 = null;
                $logoPath = public_path('images/me_logo2.png');
                
                if (file_exists($logoPath)) {
                    $logoData = file_get_contents($logoPath);
                    $logoMimeType = mime_content_type($logoPath) ?: 'image/png';
                    $logoBase64 = 'data:' . $logoMimeType . ';base64,' . base64_encode($logoData);
                }
                
                $pdf = PDF::loadView('sales.invoice-pdf-minimal', compact('sale', 'logoBase64'))
                         ->setPaper('A4', 'portrait');
                return $pdf->download('invoice-' . $sale->sale_number . '.pdf');
            } catch (\Exception $e2) {
                error_log('Sales PDF Fallback Failed: ' . $e2->getMessage() . ' | File: ' . $e2->getFile() . ' | Line: ' . $e2->getLine());
                return redirect()->back()->with('error', 'PDF generation is currently unavailable. Please try again later.');
            }
        }
    }
}
