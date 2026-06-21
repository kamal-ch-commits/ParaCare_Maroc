<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogFiltersTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_can_filter_products_by_price_range(): void
    {
        $category = Category::create([
            'name' => 'Price filter category',
        ]);

        Product::create([
            'code' => 'FLT-001',
            'name' => 'Budget Product',
            'category_id' => $category->id,
            'purchase_price' => 20,
            'sale_price' => 35,
            'stock_quantity' => 10,
            'minimum_threshold' => 2,
        ]);

        Product::create([
            'code' => 'FLT-002',
            'name' => 'Mid Product',
            'category_id' => $category->id,
            'purchase_price' => 60,
            'sale_price' => 95,
            'stock_quantity' => 10,
            'minimum_threshold' => 2,
        ]);

        Product::create([
            'code' => 'FLT-003',
            'name' => 'Premium Product',
            'category_id' => $category->id,
            'purchase_price' => 95,
            'sale_price' => 140,
            'stock_quantity' => 10,
            'minimum_threshold' => 2,
        ]);

        $response = $this->get(route('storefront.products.index', [
            'min_price' => 50,
            'max_price' => 110,
        ]));

        $response->assertOk();
        $response->assertSee('Mid Product');
        $response->assertDontSee('Budget Product');
        $response->assertDontSee('Premium Product');
    }

    public function test_catalog_can_filter_out_of_stock_products(): void
    {
        $category = Category::create([
            'name' => 'Stock filter category',
        ]);

        Product::create([
            'code' => 'STK-001',
            'name' => 'Available Product',
            'category_id' => $category->id,
            'purchase_price' => 30,
            'sale_price' => 45,
            'stock_quantity' => 7,
            'minimum_threshold' => 2,
        ]);

        Product::create([
            'code' => 'STK-002',
            'name' => 'Out Product',
            'category_id' => $category->id,
            'purchase_price' => 30,
            'sale_price' => 45,
            'stock_quantity' => 0,
            'minimum_threshold' => 2,
        ]);

        $response = $this->get(route('storefront.products.index', [
            'stock_status' => 'out_of_stock',
        ]));

        $response->assertOk();
        $response->assertSee('Out Product');
        $response->assertDontSee('Available Product');
    }
}
