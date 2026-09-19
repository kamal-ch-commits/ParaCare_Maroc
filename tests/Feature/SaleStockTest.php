<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleStockTest extends TestCase
{
    use RefreshDatabase;

    private function product(): Product
    {
        return Product::create([
            'code' => 'SALE-001',
            'name' => 'Stock test product',
            'category_id' => Category::create(['name' => 'Stock tests'])->id,
            'purchase_price' => 10,
            'sale_price' => 25,
            'stock_quantity' => 5,
            'minimum_threshold' => 1,
        ]);
    }

    public function test_sale_decreases_stock_and_records_total(): void
    {
        $product = $this->product();
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('sales.store'), [
            'sale_date' => '2026-09-19',
            'items' => [['product_id' => $product->id, 'quantity' => 2]],
        ]);

        $response->assertSessionHasNoErrors()->assertRedirect(route('sales.index'));
        $this->assertSame(3, (int) $product->fresh()->stock_quantity);
        $this->assertDatabaseCount('sales', 1);
        $this->assertDatabaseHas('sales', ['user_id' => $admin->id, 'total_amount' => 50]);
        $this->assertDatabaseHas('sale_details', [
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 25,
            'subtotal' => 50,
        ]);
    }

    public function test_insufficient_stock_rejects_sale_without_database_changes(): void
    {
        $product = $this->product();
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->from(route('sales.create'))->post(route('sales.store'), [
            'sale_date' => '2026-09-19',
            'items' => [['product_id' => $product->id, 'quantity' => 6]],
        ]);

        $response->assertRedirect(route('sales.create'));
        $response->assertSessionHasErrors('items.0.quantity');
        $this->assertSame(5, (int) $product->fresh()->stock_quantity);
        $this->assertDatabaseCount('sales', 0);
        $this->assertDatabaseCount('sale_details', 0);
    }
}
