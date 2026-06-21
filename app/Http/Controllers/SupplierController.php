<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(): View
    {
        return view('suppliers.index', [
            'suppliers' => Supplier::withCount(['products', 'stockEntries'])->latest()->paginate(10),
            'summary' => [
                'total' => Supplier::count(),
                'with_products' => Supplier::has('products')->count(),
                'with_stock_entries' => Supplier::has('stockEntries')->count(),
                'linked_products' => Supplier::withCount('products')->get()->sum('products_count'),
            ],
        ]);
    }

    public function create(): View
    {
        return view('suppliers.create', ['supplier' => new Supplier()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Supplier::create($this->validated($request));

        return redirect()->route('suppliers.index')->with('success', __('messages.supplier_created'));
    }

    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($this->validated($request, $supplier));

        return redirect()->route('suppliers.index')->with('success', __('messages.supplier_updated'));
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', __('messages.supplier_deleted'));
    }

    private function validated(Request $request, ?Supplier $supplier = null): array
    {
        $id = $supplier?->id ?? 'NULL';

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255', 'unique:suppliers,email,'.$id],
            'address' => ['nullable', 'string'],
        ]);
    }
}
