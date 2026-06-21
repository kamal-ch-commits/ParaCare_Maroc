<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Support\CartManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    private const BUY_NOW_SESSION_KEY = 'checkout.buy_now';

    private const SUCCESS_SESSION_KEY = 'checkout.success_order';

    public function startBuyNow(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $quantity = (int) ($data['quantity'] ?? 1);

        if ($product->stock_quantity < 1) {
            return back()->with('error', __('messages.product_out_of_stock'));
        }

        if ($quantity > $product->stock_quantity) {
            return back()->with('error', __('messages.quantity_unavailable', [
                'stock' => $product->stock_quantity,
            ]));
        }

        session([self::BUY_NOW_SESSION_KEY => [$product->id => $quantity]]);

        return redirect()->route('checkout.buy-now.create');
    }

    public function create(CartManager $cartManager): View|RedirectResponse
    {
        $summary = $cartManager->summary();

        if ($summary['is_empty']) {
            return redirect()->route('cart.index')->with('error', __('messages.cart_empty'));
        }

        return view('checkout.create', $this->checkoutViewData(
            summary: $summary,
            action: route('checkout.store'),
            heading: __('store.checkout_heading_cart'),
            subheading: __('store.checkout_subheading_cart'),
            returnUrl: route('cart.index')
        ));
    }

    public function createBuyNow(CartManager $cartManager): View|RedirectResponse
    {
        $summary = $cartManager->summary(session(self::BUY_NOW_SESSION_KEY, []));

        if ($summary['is_empty']) {
            return redirect()->route('storefront.products.index')->with('error', __('messages.buy_now_empty'));
        }

        return view('checkout.create', $this->checkoutViewData(
            summary: $summary,
            action: route('checkout.buy-now.store'),
            heading: __('store.checkout_heading_buy_now'),
            subheading: __('store.checkout_subheading_buy_now'),
            returnUrl: route('storefront.products.index')
        ));
    }

    public function store(StoreOrderRequest $request, CartManager $cartManager): RedirectResponse
    {
        $summary = $cartManager->summary();

        if ($summary['is_empty']) {
            return redirect()->route('cart.index')->with('error', __('messages.cart_empty'));
        }

        $order = $this->placeOrder($request->validated(), $summary['items']);

        $cartManager->clear();
        session()->put(self::SUCCESS_SESSION_KEY, $this->successPayload($order));

        return redirect()->route('checkout.success');
    }

    public function storeBuyNow(StoreOrderRequest $request, CartManager $cartManager): RedirectResponse
    {
        $summary = $cartManager->summary(session(self::BUY_NOW_SESSION_KEY, []));

        if ($summary['is_empty']) {
            return redirect()->route('storefront.products.index')->with('error', __('messages.buy_now_empty'));
        }

        $order = $this->placeOrder($request->validated(), $summary['items']);

        session()->forget(self::BUY_NOW_SESSION_KEY);
        session()->put(self::SUCCESS_SESSION_KEY, $this->successPayload($order));

        return redirect()->route('checkout.success');
    }

    public function success(): View|RedirectResponse
    {
        $order = session()->pull(self::SUCCESS_SESSION_KEY);

        if (! $order) {
            return redirect()->route('storefront.home');
        }

        return view('checkout.success', [
            'order' => $order,
        ]);
    }

    private function checkoutViewData(array $summary, string $action, string $heading, string $subheading, string $returnUrl): array
    {
        return [
            'summary' => $summary,
            'checkoutAction' => $action,
            'heading' => $heading,
            'subheading' => $subheading,
            'returnUrl' => $returnUrl,
            'customer' => [
                'name' => old('customer_name', auth()->user()?->name),
                'email' => old('customer_email', auth()->user()?->email),
                'phone' => old('customer_phone'),
            ],
            'paymentMethods' => $this->paymentMethods(),
            'selectedPaymentMethod' => old('payment_method', Order::PAYMENT_METHOD_CASH_ON_DELIVERY),
        ];
    }

    private function placeOrder(array $customerData, Collection $items): Order
    {
        return DB::transaction(function () use ($customerData, $items): Order {
            $products = Product::with('images')
                ->whereIn('id', $items->pluck('product.id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $preparedItems = [];
            $total = 0;

            foreach ($items as $index => $item) {
                $product = $products->get($item->product->id);

                if (! $product) {
                    throw ValidationException::withMessages([
                        "items.{$index}" => __('messages.cart_product_missing'),
                    ]);
                }

                if ($item->quantity > $product->stock_quantity) {
                    throw ValidationException::withMessages([
                        "items.{$index}" => __('messages.stock_insufficient_for_product', [
                            'product' => $product->translated_name,
                            'stock' => $product->stock_quantity,
                        ]),
                    ]);
                }

                $unitPrice = (float) $product->sale_price;
                $subtotal = $unitPrice * $item->quantity;
                $total += $subtotal;

                $preparedItems[] = [
                    'product' => $product,
                    'quantity' => $item->quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ];
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => $customerData['customer_name'],
                'customer_email' => $customerData['customer_email'],
                'customer_phone' => $customerData['customer_phone'] ?? null,
                'total_amount' => $total,
                'status' => 'confirmed',
                'payment_method' => $customerData['payment_method'],
                'payment_status' => Order::paymentStatusForMethod($customerData['payment_method']),
            ]);

            foreach ($preparedItems as $item) {
                $order->items()->create([
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $item['product']->decrement('stock_quantity', $item['quantity']);
            }

            return $order->load('items.product.images');
        });
    }

    private function successPayload(Order $order): array
    {
        return [
            'id' => $order->id,
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
            'customer_phone' => $order->customer_phone,
            'total_amount' => (float) $order->total_amount,
            'status' => $order->status,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'payment_reference' => $order->payment_reference,
            'created_at' => $order->created_at,
            'items' => $order->items->map(fn ($item) => (object) [
                'product' => $item->product,
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'subtotal' => (float) $item->subtotal,
            ]),
        ];
    }

    private function paymentMethods(): array
    {
        return [
            [
                'value' => Order::PAYMENT_METHOD_CASH_ON_DELIVERY,
                'icon' => 'cash-coin',
                'label' => __('store.payment_method_cash_on_delivery'),
                'description' => __('store.payment_method_cash_on_delivery_note'),
            ],
            [
                'value' => Order::PAYMENT_METHOD_BANK_TRANSFER,
                'icon' => 'bank2',
                'label' => __('store.payment_method_bank_transfer'),
                'description' => __('store.payment_method_bank_transfer_note'),
            ],
            [
                'value' => Order::PAYMENT_METHOD_PAYPAL,
                'icon' => 'paypal',
                'label' => __('store.payment_method_paypal'),
                'description' => __('store.payment_method_paypal_note'),
            ],
            [
                'value' => Order::PAYMENT_METHOD_CARD,
                'icon' => 'credit-card-2-front',
                'label' => __('store.payment_method_card'),
                'description' => __('store.payment_method_card_note'),
            ],
        ];
    }
}
