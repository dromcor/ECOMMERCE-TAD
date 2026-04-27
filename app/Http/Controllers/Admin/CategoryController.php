<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin');
    }

    public function index()
    {
        $categories = Category::with('parent')->paginate(30);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::all();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success','Categoría creada');
    }

    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category','parents'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success','Categoría actualizada');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success','Categoría eliminada');
    }
}
