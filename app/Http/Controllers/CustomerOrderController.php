<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()
            ->orders()
            ->with(['items.product.images'])
            ->latest()
            ->paginate(10);

        return view('storefront.orders.index', [
            'orders' => $orders,
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless(
            $order->user_id === $request->user()->id || $request->user()->isAdmin(),
            403
        );

        $order->load(['items.product.images', 'user']);

        return view('storefront.orders.show', [
            'order' => $order,
        ]);
    }
}
