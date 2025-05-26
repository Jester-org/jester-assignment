<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with(['products', 'promotions'])->paginate(10);
        if ($request->expectsJson()) {
            return response()->json($categories);
        }
        return view('category.index', compact('categories'));
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($request->all());
        if ($request->expectsJson()) {
            return response()->json($category, 201);
        }
        return redirect()->route('categories.index');
    }

    public function show(Request $request, Category $category)
    {
        $category->load(['products', 'promotions']);
        if ($request->expectsJson()) {
            return response()->json($category);
        }
        return view('category.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());
        if ($request->expectsJson()) {
            return response()->json($category);
        }
        return redirect()->route('categories.index');
    }

    public function destroy(Request $request, Category $category)
    {
        $category->delete();
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('categories.index');
    }
}