<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('products')->orderBy('name')->paginate(15);
        return view('brands.index', compact('brands'));
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        Brand::create($validated);

        return redirect()->route('brands.index')
                        ->with('success', 'Brand created successfully!');
    }

    public function show(Brand $brand)
    {
        $brand->load('products');
        return view('brands.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        $brand->update($validated);

        return redirect()->route('brands.index')
                        ->with('success', 'Brand updated successfully!');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->count() > 0) {
            return redirect()->route('brands.index')
                            ->with('error', 'Cannot delete brand with products. Please reassign or delete products first.');
        }

        $brand->delete();

        return redirect()->route('brands.index')
                        ->with('success', 'Brand deleted successfully!');
    }
}
