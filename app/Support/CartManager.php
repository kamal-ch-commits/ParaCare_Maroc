<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartManager
{
    private const SESSION_KEY = 'cart.items';

    public function raw(): array
    {
        return collect(session(self::SESSION_KEY, []))
            ->mapWithKeys(fn ($quantity, $productId) => [(int) $productId => max(0, (int) $quantity)])
            ->filter()
            ->all();
    }

    public function set(array $items): void
    {
        session([self::SESSION_KEY => collect($items)
            ->mapWithKeys(fn ($quantity, $productId) => [(int) $productId => max(0, (int) $quantity)])
            ->filter()
            ->all()]);
    }

    public function add(Product $product, int $quantity): int
    {
        $items = $this->raw();
        $items[$product->id] = ($items[$product->id] ?? 0) + $quantity;
        $this->set($items);

        return $items[$product->id];
    }

    public function update(Product $product, int $quantity): void
    {
        $items = $this->raw();

        if ($quantity <= 0) {
            unset($items[$product->id]);
        } else {
            $items[$product->id] = $quantity;
        }

        $this->set($items);
    }

    public function remove(Product|int $product): void
    {
        $productId = $product instanceof Product ? $product->id : $product;
        $items = $this->raw();
        unset($items[$productId]);
        $this->set($items);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return array_sum($this->raw());
    }

    public function uniqueCount(): int
    {
        return count($this->raw());
    }

    public function items(?array $source = null): Collection
    {
        $quantities = collect($source ?? $this->raw())
            ->mapWithKeys(fn ($quantity, $productId) => [(int) $productId => max(0, (int) $quantity)])
            ->filter();

        if ($quantities->isEmpty()) {
            return collect();
        }

        $products = Product::with(['category', 'images'])
            ->whereIn('id', $quantities->keys())
            ->get()
            ->keyBy('id');

        if ($source === null && $products->count() !== $quantities->count()) {
            $this->set(
                $quantities
                    ->filter(fn ($quantity, $productId) => $products->has($productId))
                    ->all()
            );
        }

        return $quantities
            ->map(function (int $quantity, int $productId) use ($products) {
                $product = $products->get($productId);

                if (! $product) {
                    return null;
                }

                $unitPrice = (float) $product->sale_price;

                return (object) [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $unitPrice * $quantity,
                ];
            })
            ->filter()
            ->values();
    }

    public function summary(?array $source = null): array
    {
        $items = $this->items($source);

        return [
            'items' => $items,
            'item_count' => $items->sum('quantity'),
            'unique_count' => $items->count(),
            'total' => $items->sum('subtotal'),
            'is_empty' => $items->isEmpty(),
        ];
    }
}
