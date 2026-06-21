<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockEntry;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockEntryController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $stockEntries = StockEntry::with(['product', 'supplier'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('product', function ($productQuery) use ($search) {
                    $productQuery->searchLocalized($search, ['name']);
                })->orWhereHas('supplier', function ($supplierQuery) use ($search) {
                    $supplierQuery->where('name', 'like', "%{$search}%");
                });
            })
            ->latest('entry_date')
            ->paginate(12)
            ->withQueryString();

        return view('stock_entries.index', compact('stockEntries', 'search'))
            ->with([
                'summary' => [
                    'entries_count' => StockEntry::count(),
                    'quantity_received' => (int) StockEntry::sum('quantity'),
                    'month_quantity' => (int) StockEntry::where('entry_date', '>=', now()->subDays(30))->sum('quantity'),
                    'suppliers_count' => StockEntry::whereNotNull('supplier_id')->distinct('supplier_id')->count('supplier_id'),
                    'average_purchase_price' => (float) StockEntry::avg('purchase_price'),
                ],
            ]);
    }

    public function create(): View
    {
        return view('stock_entries.create', [
            'products' => Product::with('category')->orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'entry_date' => ['required', 'date'],
        ]);

        DB::transaction(function () use ($data): void {
            StockEntry::create($data);

            Product::whereKey($data['product_id'])->increment('stock_quantity', $data['quantity']);
            Product::whereKey($data['product_id'])->update(['purchase_price' => $data['purchase_price']]);
        });

        return redirect()->route('stock-entries.index')->with('success', __('messages.stock_entry_created'));
    }
}
