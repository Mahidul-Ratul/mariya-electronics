<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%")
                  ->orWhere('model', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('brand_id') && $request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }
        
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->has('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->where('stock_quantity', '>', 0);
                    break;
                case 'low_stock':
                    $query->where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0);
                    break;
                case 'out_of_stock':
                    $query->where('stock_quantity', 0);
                    break;
            }
        }
        
        $products = $query->orderBy('name')->paginate(15);
        $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
        $brands = \App\Models\Brand::where('is_active', true)->orderBy('name')->get();
        
        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
        $brands = \App\Models\Brand::where('is_active', true)->orderBy('name')->get();
        return view('products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'warranty_period' => 'nullable|string|max:100',
            'is_active' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $validated['image'] = 'uploads/products/' . $imageName;
        }

        // Populate category field from category_id if provided
        if ($validated['category_id']) {
            $category = \App\Models\Category::find($validated['category_id']);
            if ($category) {
                $validated['category'] = $category->name;
            }
        }

        // Populate brand field from brand_id if provided
        if ($validated['brand_id']) {
            $brand = \App\Models\Brand::find($validated['brand_id']);
            if ($brand) {
                $validated['brand'] = $brand->name;
            }
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        $salesHistory = $product->sales()->with('customer')->latest()->limit(10)->get();
        return view('products.show', compact('product', 'salesHistory'));
    }

    public function edit(Product $product)
    {
        $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
        $brands = \App\Models\Brand::where('is_active', true)->orderBy('name')->get();
        return view('products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'warranty_period' => 'nullable|string|max:100',
            'is_active' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $validated['image'] = 'uploads/products/' . $imageName;
        }

        // Populate category field from category_id if provided
        if ($validated['category_id']) {
            $category = \App\Models\Category::find($validated['category_id']);
            if ($category) {
                $validated['category'] = $category->name;
            }
        }

        // Populate brand field from brand_id if provided
        if ($validated['brand_id']) {
            $brand = \App\Models\Brand::find($validated['brand_id']);
            if ($brand) {
                $validated['brand'] = $brand->name;
            }
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Delete image file if exists
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }
        
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    public function lowStock()
    {
        $products = Product::where('stock_quantity', '<=', 10)
                          ->orderBy('stock_quantity')
                          ->get();
        return view('products.low-stock', compact('products'));
    }

    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'stock_quantity' => 'required|integer|min:0'
        ]);

        $product->update($validated);

        return response()->json(['success' => true, 'message' => 'Stock updated successfully!']);
    }

    public function search(Request $request)
    {
        $query = Product::query();
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%")
                  ->orWhere('model', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('brand_id') && $request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }
        
        if ($request->has('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->where('stock_quantity', '>', 0);
                    break;
                case 'low_stock':
                    $query->where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0);
                    break;
                case 'out_of_stock':
                    $query->where('stock_quantity', 0);
                    break;
            }
        }
        
        $products = $query->orderBy('name')->paginate(15);
        
        return view('products.partials.product-table', compact('products'))->render();
    }
}
