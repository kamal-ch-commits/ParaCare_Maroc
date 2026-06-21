<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('categories.index', [
            'categories' => Category::withCount('products')->latest()->paginate(10),
            'summary' => [
                'total' => Category::count(),
                'with_products' => Category::has('products')->count(),
                'empty' => Category::doesntHave('products')->count(),
                'linked_products' => Category::withCount('products')->get()->sum('products_count'),
            ],
        ]);
    }

    public function create(): View
    {
        return view('categories.create', ['category' => new Category()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Category::create($this->validated($request));

        return redirect()->route('categories.index')->with('success', __('messages.category_created'));
    }

    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $category->update($this->validated($request, $category));

        return redirect()->route('categories.index')->with('success', __('messages.category_updated'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->with('error', __('messages.category_delete_blocked'));
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', __('messages.category_deleted'));
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        $id = $category?->id ?? 'NULL';

        return $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,'.$id],
            'description' => ['nullable', 'string'],
        ]);
    }
}
