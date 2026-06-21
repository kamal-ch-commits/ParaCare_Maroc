<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $sales = Sale::with(['user', 'details.product'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('details.product', function ($productQuery) use ($search) {
                    $productQuery->searchLocalized($search, ['name']);
                });
            })
            ->latest('sale_date')
            ->paginate(10)
            ->withQueryString();

        return view('sales.index', compact('sales', 'search'))
            ->with([
                'summary' => [
                    'total_sales' => (float) Sale::sum('total_amount'),
                    'sales_count' => Sale::count(),
                    'today_sales' => (float) Sale::whereDate('sale_date', today())->sum('total_amount'),
                    'month_sales' => (float) Sale::where('sale_date', '>=', now()->subDays(30))->sum('total_amount'),
                    'items_sold' => (int) SaleDetail::sum('quantity'),
                ],
            ]);
    }

    public function create(): View
    {
        return view('sales.create', [
            'products' => Product::where('stock_quantity', '>', 0)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'sale_date' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $items = collect($data['items'])
            ->filter(fn (array $item): bool => filled($item['product_id'] ?? null) && (int) ($item['quantity'] ?? 0) > 0)
            ->values();

        if ($items->isEmpty()) {
            throw ValidationException::withMessages(['items' => __('messages.sale_requires_item')]);
        }

        DB::transaction(function () use ($data, $items): void {
            $products = Product::whereIn('id', $items->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;
            $preparedItems = [];

            foreach ($items as $index => $item) {
                $product = $products->get((int) $item['product_id']);
                $quantity = (int) $item['quantity'];

                if (! $product) {
                    throw ValidationException::withMessages(["items.{$index}.product_id" => __('messages.product_not_found')]);
                }

                if ($quantity > $product->stock_quantity) {
                    throw ValidationException::withMessages([
                        "items.{$index}.quantity" => __('messages.stock_insufficient_for_product', [
                            'product' => $product->translated_name,
                            'stock' => $product->stock_quantity,
                        ]),
                    ]);
                }

                $subtotal = $quantity * (float) $product->sale_price;
                $total += $subtotal;

                $preparedItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $product->sale_price,
                    'subtotal' => $subtotal,
                ];
            }

            $sale = Sale::create([
                'user_id' => auth()->id(),
                'sale_date' => $data['sale_date'],
                'total_amount' => $total,
            ]);

            foreach ($preparedItems as $item) {
                $sale->details()->create([
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $item['product']->decrement('stock_quantity', $item['quantity']);
            }
        });

        return redirect()->route('sales.index')->with('success', __('messages.sale_created'));
    }

    public function show(Sale $sale): View
    {
        return view('sales.show', [
            'sale' => $sale->load(['user', 'details.product']),
        ]);
    }
}
