<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockEntry;
use App\Models\Supplier;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalSuppliers' => Supplier::count(),
            'outOfStockCount' => Product::outOfStock()->count(),
            'lowStockProducts' => Product::with('category')->lowStock()->orderBy('stock_quantity')->limit(8)->get(),
            'closeToExpirationProducts' => Product::with('category')->closeToExpiration()->orderBy('expiration_date')->limit(8)->get(),
            'expiredProducts' => Product::with('category')->expired()->orderBy('expiration_date')->limit(8)->get(),
            'closeToExpirationCount' => Product::closeToExpiration()->count(),
            'totalSales' => Sale::sum('total_amount'),
            'salesCount' => Sale::count(),
            'stockEntriesCount' => StockEntry::count(),
            'recentSales' => Sale::with('user')->latest('sale_date')->limit(5)->get(),
            'ordersCount' => Order::count(),
            'pendingPaymentOrdersCount' => Order::whereIn('payment_status', ['pending', 'awaiting_transfer', 'cash_on_delivery'])->count(),
            'recentOrders' => Order::with('user')->latest()->limit(5)->get(),
        ]);
    }
}
