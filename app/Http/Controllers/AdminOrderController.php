<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminOrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with(['items', 'user'])
            ->withCount('items')
            ->latest()
            ->paginate(12);

        $pendingPaymentStatuses = [
            Order::PAYMENT_STATUS_PENDING,
            Order::PAYMENT_STATUS_AWAITING_TRANSFER,
            Order::PAYMENT_STATUS_CASH_ON_DELIVERY,
        ];

        $statusBreakdown = Order::query()
            ->select('payment_status')
            ->selectRaw('COUNT(*) as orders_count')
            ->selectRaw('SUM(total_amount) as total_amount')
            ->groupBy('payment_status')
            ->get()
            ->keyBy('payment_status');

        $methodBreakdown = Order::query()
            ->select('payment_method')
            ->selectRaw('COUNT(*) as orders_count')
            ->selectRaw('SUM(total_amount) as total_amount')
            ->groupBy('payment_method')
            ->get()
            ->keyBy('payment_method');

        $totalOrders = Order::count();
        $totalRevenue = (float) Order::sum('total_amount');

        return view('admin.orders.index', [
            'orders' => $orders,
            'summary' => [
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'pending_payments' => Order::whereIn('payment_status', $pendingPaymentStatuses)->count(),
                'today_orders' => Order::whereDate('created_at', today())->count(),
                'last_30_days_revenue' => (float) Order::where('created_at', '>=', now()->subDays(30))->sum('total_amount'),
                'average_order_value' => $totalOrders > 0 ? $totalRevenue / $totalOrders : 0,
            ],
            'paymentStatuses' => Order::paymentStatuses(),
            'paymentMethods' => Order::paymentMethods(),
            'statusBreakdown' => $statusBreakdown,
            'methodBreakdown' => $methodBreakdown,
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['items.product.images', 'user']);

        return view('admin.orders.show', [
            'order' => $order,
            'paymentStatuses' => Order::paymentStatuses(),
        ]);
    }

    public function updatePaymentStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'string', 'in:'.implode(',', Order::paymentStatuses())],
            'payment_reference' => ['nullable', 'string', 'max:255'],
        ]);

        $order->update([
            'payment_status' => $validated['payment_status'],
            'payment_reference' => $validated['payment_reference'] ?: null,
        ]);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', __('admin.payment_status_updated'));
    }
}
