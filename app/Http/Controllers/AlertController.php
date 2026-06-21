<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlertController extends Controller
{
    public function lowStock(): View
    {
        return view('alerts.low-stock', [
            'products' => Product::with(['category', 'supplier'])
                ->lowStock()
                ->orderBy('stock_quantity')
                ->paginate(12),
            'summary' => [
                'low_stock' => Product::lowStock()->count(),
                'out_of_stock' => Product::outOfStock()->count(),
                'available_low_stock' => Product::lowStock()->where('stock_quantity', '>', 0)->count(),
                'total_products' => Product::count(),
            ],
        ]);
    }

    public function expiration(Request $request): View
    {
        $from = $request->date('from')?->toDateString();
        $to = $request->date('to')?->toDateString();
        $status = $request->string('status')->toString();

        $products = Product::with(['category', 'supplier'])
            ->whereNotNull('expiration_date')
            ->when($status === 'expired', fn ($query) => $query->expired())
            ->when($status === 'soon', fn ($query) => $query->closeToExpiration())
            ->when($from, fn ($query) => $query->whereDate('expiration_date', '>=', $from))
            ->when($to, fn ($query) => $query->whereDate('expiration_date', '<=', $to))
            ->orderBy('expiration_date')
            ->paginate(12)
            ->withQueryString();

        return view('alerts.expiration', compact('products', 'from', 'to', 'status'))
            ->with([
                'summary' => [
                    'tracked' => Product::whereNotNull('expiration_date')->count(),
                    'expired' => Product::expired()->count(),
                    'soon' => Product::closeToExpiration()->count(),
                    'valid' => Product::whereNotNull('expiration_date')
                        ->whereDate('expiration_date', '>', now()->addDays(30)->toDateString())
                        ->count(),
                ],
            ]);
    }
}
