<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\StockEntry;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HistoryController extends Controller
{
    public function stockEntries(Request $request): View
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
            ->paginate(15)
            ->withQueryString();

        return view('history.stock-entries', compact('stockEntries', 'search'))
            ->with([
                'summary' => [
                    'entries_count' => StockEntry::count(),
                    'quantity_received' => (int) StockEntry::sum('quantity'),
                    'month_quantity' => (int) StockEntry::where('entry_date', '>=', now()->subDays(30))->sum('quantity'),
                    'average_purchase_price' => (float) StockEntry::avg('purchase_price'),
                ],
            ]);
    }

    public function sales(Request $request): View
    {
        $search = $request->string('search')->toString();

        $sales = Sale::with(['user', 'details.product'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('details.product', function ($productQuery) use ($search) {
                    $productQuery->searchLocalized($search, ['name']);
                });
            })
            ->latest('sale_date')
            ->paginate(15)
            ->withQueryString();

        return view('history.sales', compact('sales', 'search'))
            ->with([
                'summary' => [
                    'total_sales' => (float) Sale::sum('total_amount'),
                    'sales_count' => Sale::count(),
                    'month_sales' => (float) Sale::where('sale_date', '>=', now()->subDays(30))->sum('total_amount'),
                    'items_sold' => (int) SaleDetail::sum('quantity'),
                ],
            ]);
    }
}
