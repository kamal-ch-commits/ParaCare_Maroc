<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Sale;
use App\Models\StockEntry;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminDashboardDemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $customers = User::whereIn('email', ['sara@example.com', 'youssef@example.com'])->get()->keyBy('email');
        $products = Product::with('supplier')->orderBy('id')->take(8)->get();
        $supplier = Supplier::orderBy('id')->first();

        if (! $admin || $products->count() < 3) {
            return;
        }

        $this->shapeDashboardProductStates($products);
        $this->seedStockEntries($products, $supplier);
        $this->seedSales($admin, $products);
        $this->seedOrders($customers, $products);
        $this->seedReviews($customers, $products);
    }

    private function shapeDashboardProductStates($products): void
    {
        $states = [
            ['stock_quantity' => 2, 'minimum_threshold' => 8, 'expiration_date' => now()->addDays(18)],
            ['stock_quantity' => 0, 'minimum_threshold' => 6, 'expiration_date' => now()->addMonths(5)],
            ['stock_quantity' => 5, 'minimum_threshold' => 10, 'expiration_date' => now()->subDays(12)],
            ['stock_quantity' => 14, 'minimum_threshold' => 6, 'expiration_date' => now()->addDays(9)],
            ['stock_quantity' => 28, 'minimum_threshold' => 10, 'expiration_date' => now()->addMonths(8)],
        ];

        foreach ($states as $index => $state) {
            $product = $products->get($index);

            if ($product) {
                $product->forceFill($state)->save();
            }
        }
    }

    private function seedStockEntries($products, ?Supplier $supplier): void
    {
        foreach ($products->take(5)->values() as $index => $product) {
            StockEntry::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'entry_date' => now()->subDays(14 - ($index * 2))->toDateString(),
                ],
                [
                    'supplier_id' => $product->supplier_id ?: $supplier?->id,
                    'quantity' => 18 + ($index * 7),
                    'purchase_price' => $product->purchase_price ?: max(((float) $product->sale_price) * 0.65, 10),
                ]
            );
        }
    }

    private function seedSales(User $admin, $products): void
    {
        $saleDefinitions = [
            ['date' => now()->subDays(1)->setTime(10, 15), 'items' => [[0, 2], [3, 1]]],
            ['date' => now()->subDays(3)->setTime(16, 40), 'items' => [[1, 1], [4, 2]]],
            ['date' => now()->subDays(8)->setTime(12, 5), 'items' => [[2, 1], [5, 1]]],
        ];

        foreach ($saleDefinitions as $definition) {
            $preparedItems = collect($definition['items'])->map(function (array $item) use ($products): array {
                $product = $products->get($item[0]) ?? $products->first();
                $quantity = $item[1];
                $unitPrice = (float) $product->sale_price;

                return compact('product', 'quantity', 'unitPrice') + [
                    'subtotal' => $quantity * $unitPrice,
                ];
            });

            $sale = Sale::updateOrCreate(
                [
                    'user_id' => $admin->id,
                    'sale_date' => $definition['date'],
                ],
                [
                    'total_amount' => $preparedItems->sum('subtotal'),
                ]
            );

            foreach ($preparedItems as $item) {
                $sale->details()->updateOrCreate(
                    ['product_id' => $item['product']->id],
                    [
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unitPrice'],
                        'subtotal' => $item['subtotal'],
                    ]
                );
            }
        }
    }

    private function seedOrders($customers, $products): void
    {
        $orderDefinitions = [
            [
                'reference' => 'DEMO-PAYPAL-001',
                'customer' => $customers->get('sara@example.com'),
                'fallback_name' => 'Sara Client',
                'fallback_email' => 'sara@example.com',
                'phone' => '0611223344',
                'method' => Order::PAYMENT_METHOD_PAYPAL,
                'status' => Order::PAYMENT_STATUS_PENDING,
                'date' => now()->subHours(6),
                'items' => [[0, 1], [4, 1]],
            ],
            [
                'reference' => 'DEMO-COD-002',
                'customer' => $customers->get('youssef@example.com'),
                'fallback_name' => 'Youssef Client',
                'fallback_email' => 'youssef@example.com',
                'phone' => '0677889900',
                'method' => Order::PAYMENT_METHOD_CASH_ON_DELIVERY,
                'status' => Order::PAYMENT_STATUS_CASH_ON_DELIVERY,
                'date' => now()->subDay()->setTime(17, 35),
                'items' => [[1, 1], [3, 2]],
            ],
            [
                'reference' => 'DEMO-BANK-003',
                'customer' => null,
                'fallback_name' => 'Nadia El Amrani',
                'fallback_email' => 'nadia.demo@example.com',
                'phone' => '0655443322',
                'method' => Order::PAYMENT_METHOD_BANK_TRANSFER,
                'status' => Order::PAYMENT_STATUS_AWAITING_TRANSFER,
                'date' => now()->subDays(2)->setTime(11, 20),
                'items' => [[2, 1], [5, 1]],
            ],
            [
                'reference' => 'DEMO-CARD-004',
                'customer' => null,
                'fallback_name' => 'Imane Zahra',
                'fallback_email' => 'imane.demo@example.com',
                'phone' => '0666001122',
                'method' => Order::PAYMENT_METHOD_CARD,
                'status' => Order::PAYMENT_STATUS_PAID,
                'date' => now()->subDays(5)->setTime(14, 50),
                'items' => [[4, 2], [6, 1]],
            ],
        ];

        foreach ($orderDefinitions as $definition) {
            $preparedItems = collect($definition['items'])->map(function (array $item) use ($products): array {
                $product = $products->get($item[0]) ?? $products->first();
                $quantity = $item[1];
                $unitPrice = (float) $product->sale_price;

                return compact('product', 'quantity', 'unitPrice') + [
                    'subtotal' => $quantity * $unitPrice,
                ];
            });

            $order = Order::updateOrCreate(
                ['payment_reference' => $definition['reference']],
                [
                    'user_id' => $definition['customer']?->id,
                    'customer_name' => $definition['customer']?->name ?? $definition['fallback_name'],
                    'customer_email' => $definition['customer']?->email ?? $definition['fallback_email'],
                    'customer_phone' => $definition['phone'],
                    'total_amount' => $preparedItems->sum('subtotal'),
                    'status' => 'confirmed',
                    'payment_method' => $definition['method'],
                    'payment_status' => $definition['status'],
                ]
            );

            $order->forceFill([
                'created_at' => $definition['date'],
                'updated_at' => $definition['date'],
            ])->save();

            foreach ($preparedItems as $item) {
                $order->items()->updateOrCreate(
                    ['product_id' => $item['product']->id],
                    [
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unitPrice'],
                        'subtotal' => $item['subtotal'],
                    ]
                );
            }
        }
    }

    private function seedReviews($customers, $products): void
    {
        $reviewDefinitions = [
            ['email' => 'sara@example.com', 'product_index' => 0, 'rating' => 5, 'status' => ProductReview::STATUS_APPROVED, 'comment' => 'Produit conforme et livraison rapide.'],
            ['email' => 'youssef@example.com', 'product_index' => 1, 'rating' => 4, 'status' => ProductReview::STATUS_PENDING, 'comment' => 'Bon produit, emballage propre.'],
            ['email' => null, 'guest_name' => 'Client invite', 'product_index' => 2, 'rating' => 3, 'status' => ProductReview::STATUS_REJECTED, 'comment' => 'Avis a verifier avant publication.'],
        ];

        foreach ($reviewDefinitions as $definition) {
            $product = $products->get($definition['product_index']) ?? $products->first();
            $user = isset($definition['email']) ? $customers->get($definition['email']) : null;

            ProductReview::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'user_id' => $user?->id,
                    'guest_name' => $user ? null : ($definition['guest_name'] ?? 'Client invite'),
                ],
                [
                    'rating' => $definition['rating'],
                    'comment' => $definition['comment'],
                    'status' => $definition['status'],
                ]
            );
        }
    }
}
