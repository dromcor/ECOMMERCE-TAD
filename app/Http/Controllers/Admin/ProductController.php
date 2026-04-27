<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin');
    }

    public function index()
    {
        $products = Product::paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'price_cents' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'activo' => 'sometimes|boolean',
            'categories' => 'sometimes|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        $product = Product::create($data);
        if (! empty($data['categories'])) {
            $product->categories()->sync($data['categories']);
        }
        return redirect()->route('admin.products.index')->with('success','Producto creado');
    }

    public function edit(Product $product)
    {
        $categories = \App\Models\Category::all();
        return view('admin.products.edit', compact('product','categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'price_cents' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'activo' => 'sometimes|boolean',
            'categories' => 'sometimes|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);
        $product->update($data);
        if (array_key_exists('categories', $data)) {
            $product->categories()->sync($data['categories'] ?? []);
        }
        return redirect()->route('admin.products.index')->with('success','Producto actualizado');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success','Producto eliminado');
    }
}
