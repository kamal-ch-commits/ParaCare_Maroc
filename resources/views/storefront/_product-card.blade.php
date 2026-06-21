@php
    $productBadge = $product->created_at && $product->created_at->gt(now()->subDays(45))
        ? ['label' => __('store.badge_new'), 'class' => 'status-badge-soft', 'icon' => 'bi bi-stars']
        : ($product->stock_quantity > max(($product->minimum_threshold ?? 0) + 4, 8)
            ? ['label' => __('store.badge_popular'), 'class' => 'status-badge-warm', 'icon' => 'bi bi-fire']
            : ['label' => __('store.badge_in_stock'), 'class' => 'status-badge-cool', 'icon' => 'bi bi-check2-circle']);
@endphp

<div class="store-card product-card h-100 p-3 p-lg-4">
    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
        <span class="status-badge {{ $productBadge['class'] }}"><i class="{{ $productBadge['icon'] }}"></i> {{ $productBadge['label'] }}</span>
        <span class="product-price-chip">{{ number_format($product->sale_price, 2) }} DH</span>
    </div>

    <a href="{{ route('storefront.products.show', $product) }}" class="product-thumb-link">
        <div class="product-thumb-frame mb-3">
            <img src="{{ $product->image_url }}" alt="{{ $product->translated_name }}" class="product-thumb">
        </div>
    </a>

    <div class="mb-2">
        <div class="small text-secondary text-uppercase fw-semibold mb-1">
            {{ $product->category?->translated_name ?? __('store.without_category') }}
        </div>
        <h3 class="h5 mb-1 product-card-title">
            <a href="{{ route('storefront.products.show', $product) }}">{{ $product->translated_name }}</a>
        </h3>
    </div>

    <div class="mb-3">
        @include('storefront._rating-stars', [
            'stars' => $product->rating_stars,
            'value' => $product->average_rating,
            'count' => $product->review_count,
            'sizeClass' => 'rating-sm',
            'showValue' => $product->review_count > 0,
        ])
        @if ($product->review_count === 0)
            <div class="small text-secondary mt-1">{{ __('store.no_reviews_short') }}</div>
        @endif
    </div>

    <p class="muted-copy small mb-3">{{ \Illuminate\Support\Str::limit($product->translated_description ?: __('store.product_fallback_description'), 110) }}</p>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <span class="mini-badge"><i class="bi bi-tags"></i> {{ $product->category?->translated_name ?? __('store.without_category') }}</span>
        <span class="mini-badge"><i class="bi bi-box-seam"></i> {{ $product->stock_quantity > 0 ? __('store.in_stock', ['count' => $product->stock_quantity]) : __('store.out_of_stock_label') }}</span>
    </div>

    <div class="surface-divider"></div>

    <div class="mt-auto d-grid gap-2">
        <a href="{{ route('storefront.products.show', $product) }}" class="btn btn-outline-primary btn-sm">{{ __('store.view_product') }}</a>
        <div class="d-flex gap-2 flex-column flex-sm-row">
            <form method="POST" action="{{ route('cart.store', $product) }}" class="flex-fill">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn btn-primary btn-sm w-100" @disabled($product->stock_quantity < 1)>{{ __('store.add_to_cart') }}</button>
            </form>
            <form method="POST" action="{{ route('checkout.buy-now', $product) }}" class="flex-fill">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn btn-outline-primary btn-sm w-100" @disabled($product->stock_quantity < 1)>{{ __('store.buy_now') }}</button>
            </form>
        </div>
    </div>
</div>
