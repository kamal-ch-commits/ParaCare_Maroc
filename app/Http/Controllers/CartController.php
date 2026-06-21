<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\CartManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(CartManager $cartManager): View
    {
        return view('cart.index', $cartManager->summary());
    }

    public function store(Request $request, Product $product, CartManager $cartManager): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $quantity = (int) ($data['quantity'] ?? 1);

        $existingQuantity = $cartManager->raw()[$product->id] ?? 0;
        $requestedQuantity = $existingQuantity + $quantity;

        if ($product->stock_quantity < 1) {
            return back()->with('error', __('messages.product_out_of_stock'));
        }

        if ($requestedQuantity > $product->stock_quantity) {
            return back()->with('error', __('messages.quantity_unavailable', [
                'stock' => $product->stock_quantity,
            ]));
        }

        $cartManager->add($product, $quantity);

        return back()->with('success', __('messages.product_added_to_cart', ['product' => $product->translated_name]));
    }

    public function update(Request $request, Product $product, CartManager $cartManager): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $quantity = (int) $data['quantity'];

        if ($quantity > $product->stock_quantity) {
            return back()->with('error', __('messages.quantity_unavailable', [
                'stock' => $product->stock_quantity,
            ]));
        }

        $cartManager->update($product, $quantity);

        return back()->with('success', __('messages.cart_updated'));
    }

    public function destroy(Product $product, CartManager $cartManager): RedirectResponse
    {
        $cartManager->remove($product);

        return back()->with('success', __('messages.product_removed_from_cart'));
    }

    public function clear(CartManager $cartManager): RedirectResponse
    {
        $cartManager->clear();

        return back()->with('success', __('messages.cart_cleared'));
    }
}
