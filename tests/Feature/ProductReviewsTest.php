<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductReviewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_submit_a_product_review(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $category = Category::create([
            'name' => 'Reviews category',
        ]);

        $product = Product::create([
            'code' => 'REV-001',
            'name' => 'Review Product',
            'category_id' => $category->id,
            'purchase_price' => 50,
            'sale_price' => 75,
            'stock_quantity' => 10,
            'minimum_threshold' => 2,
            'description' => 'Review test product',
        ]);

        $response = $this->actingAs($customer)
            ->from(route('storefront.products.show', $product))
            ->post(route('storefront.products.reviews.store', $product), [
                'rating' => 5,
                'comment' => 'Excellent produit pour un usage quotidien.',
            ])
            ->assertRedirect(route('storefront.products.show', $product));

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('product_reviews', [
            'product_id' => $product->id,
            'user_id' => $customer->id,
            'rating' => 5,
            'status' => ProductReview::STATUS_APPROVED,
        ]);

        $this->get(route('storefront.products.show', $product))
            ->assertOk()
            ->assertSee('Excellent produit pour un usage quotidien.')
            ->assertSee('Review Product');
    }

    public function test_review_submission_requires_valid_rating_and_comment(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $category = Category::create([
            'name' => 'Validation category',
        ]);

        $product = Product::create([
            'code' => 'REV-002',
            'name' => 'Validation Product',
            'category_id' => $category->id,
            'purchase_price' => 40,
            'sale_price' => 65,
            'stock_quantity' => 8,
            'minimum_threshold' => 2,
        ]);

        $this->actingAs($customer)
            ->from(route('storefront.products.show', $product))
            ->post(route('storefront.products.reviews.store', $product), [
                'rating' => 6,
                'comment' => '',
            ])
            ->assertRedirect(route('storefront.products.show', $product))
            ->assertSessionHasErrors(['rating', 'comment']);

        $this->assertDatabaseCount('product_reviews', 0);
    }
}
