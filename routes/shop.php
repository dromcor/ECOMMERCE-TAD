<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;

Route::get('/productos', function () {
    $productos = Product::paginate(9);
    return view('products.index', compact('productos'));
})->name('products.index');

Route::get('/productos/{id}', function ($id) {
    $producto = Product::findOrFail($id);
    return view('products.show', compact('producto'));
})->name('products.show');
