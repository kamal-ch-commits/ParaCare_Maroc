<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductReviewRequest;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;

class ProductReviewController extends Controller
{
    public function store(StoreProductReviewRequest $request, Product $product): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user?->isCustomer(), 403);

        ProductReview::updateOrCreate(
            [
                'product_id' => $product->id,
                'user_id' => $user->id,
            ],
            [
                'guest_name' => null,
                'rating' => (int) $request->validated('rating'),
                'comment' => trim($request->validated('comment')),
                'status' => ProductReview::STATUS_APPROVED,
            ]
        );

        return redirect()
            ->route('storefront.products.show', $product)
            ->with('success', __('messages.review_submitted'));
    }
}
