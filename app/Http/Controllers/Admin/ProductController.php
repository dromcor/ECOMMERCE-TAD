<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('categories')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('nombre')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|string|max:1000',
            'activo' => 'nullable',
            'categorias' => 'nullable|array',
        ]);

        $producto = Product::create([
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion'],
            'price_cents' => (int) round($datos['precio'] * 100),
            'stock' => $datos['stock'],
            'activo' => $request->has('activo'),
            'images' => !empty($datos['imagen']) ? [$datos['imagen']] : [],
        ]);

        if ($request->has('categorias')) {
            $producto->categories()->attach($datos['categorias']);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function show(Product $product)
    {
        return redirect()->route('admin.products.edit', $product);
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('nombre')->get();
        $product->load('categories');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|string|max:1000',
            'activo' => 'nullable',
            'categorias' => 'nullable|array',
        ]);

        $product->nombre = $datos['nombre'];
        $product->descripcion = $datos['descripcion'];
        $product->price_cents = (int) round($datos['precio'] * 100);
        $product->stock = $datos['stock'];
        $product->activo = $request->has('activo');
        $product->images = !empty($datos['imagen']) ? [$datos['imagen']] : [];
        $product->save();

        if ($request->has('categorias')) {
            $product->categories()->sync($datos['categorias']);
        } else {
            $product->categories()->sync([]);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        $product->categories()->detach();
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}