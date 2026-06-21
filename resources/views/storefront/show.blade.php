@extends('layouts.storefront')

@section('title', $product->translated_name)

@section('content')
@php($galleryImages = $product->gallery_images)
<section class="hero-panel p-4 p-lg-5 mb-5">
    <div class="row g-4 align-items-start">
        <div class="col-lg-6">
            <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                <span class="status-badge status-badge-cool"><i class="bi bi-check2-circle"></i> {{ __('store.badge_in_stock') }}</span>
                <span class="catalog-pill"><i class="bi bi-image"></i> {{ __('store.product_gallery_label', ['count' => max($galleryImages->count(), 1)]) }}</span>
                <span class="catalog-pill"><i class="bi bi-tags"></i> {{ $product->category?->translated_name ?? __('store.without_category') }}</span>
            </div>

            <div class="product-gallery-frame mb-3">
                <img
                    src="{{ $product->image_url }}"
                    alt="{{ $product->translated_name }}"
                    class="product-gallery-image"
                    id="primaryProductImage"
                >
            </div>

            @if ($galleryImages->count() > 1)
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($galleryImages as $image)
                        <button
                            type="button"
                            class="thumbnail-button @if ($loop->first) active @endif"
                            data-gallery-thumb
                            data-image-url="{{ $image->image_url }}"
                            data-image-alt="{{ $product->translated_name }} - {{ __('common.view') }} {{ $loop->iteration }}"
                        >
                            <img src="{{ $image->image_url }}" alt="{{ $product->translated_name }} {{ __('common.image') }} {{ $loop->iteration }}">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="col-lg-6">
            <div class="soft-panel p-4 p-lg-5">
                <span class="eyebrow mb-3"><i class="bi bi-patch-check"></i> {{ __('store.available_product') }}</span>
                <div class="d-flex flex-column flex-md-row align-items-md-start justify-content-between gap-3 mb-3">
                    <div>
                        <h1 class="display-6 fw-bold mb-2">{{ $product->translated_name }}</h1>
                        <div class="product-price-chip">{{ number_format($product->sale_price, 2) }} DH</div>
                    </div>
                    <div class="text-md-end">
                        @include('storefront._rating-stars', [
                            'stars' => $product->rating_stars,
                            'value' => $product->average_rating,
                            'count' => $product->review_count,
                            'showValue' => $product->review_count > 0,
                        ])
                        @if ($product->review_count === 0)
                            <div class="small text-secondary mt-1">{{ __('store.no_reviews_short') }}</div>
                        @endif
                    </div>
                </div>

                <p class="muted-copy mb-4">
                    {{ $product->translated_description ?: __('store.product_description_fallback') }}
                </p>

                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <div class="detail-fact-card">
                            <div class="small text-secondary mb-1">{{ __('store.highlight_availability') }}</div>
                            <div class="fw-semibold">{{ __('store.stock_label', ['stock' => $product->stock_quantity]) }}</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="detail-fact-card">
                            <div class="small text-secondary mb-1">{{ __('store.highlight_price') }}</div>
                            <div class="fw-semibold">{{ number_format($product->sale_price, 2) }} DH</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="detail-fact-card">
                            <div class="small text-secondary mb-1">{{ __('store.highlight_category') }}</div>
                            <div class="fw-semibold">{{ $product->category?->translated_name ?? __('store.without_category') }}</div>
                        </div>
                    </div>
                </div>

                <div class="detail-purchase-panel mb-4">
                    <form method="POST" action="{{ route('cart.store', $product) }}" class="row g-3 align-items-end">
                        @csrf
                        <div class="col-sm-4">
                            <label class="form-label" for="quantity">{{ __('common.quantity') }}</label>
                            <input
                                type="number"
                                min="1"
                                max="{{ max($product->stock_quantity, 1) }}"
                                id="quantity"
                                name="quantity"
                                value="{{ old('quantity', 1) }}"
                                class="form-control"
                                @disabled($product->stock_quantity < 1)
                            >
                        </div>
                        <div class="col-sm-8 d-flex flex-column flex-sm-row gap-3">
                            <button type="submit" class="btn btn-primary flex-fill" @disabled($product->stock_quantity < 1)>{{ __('store.add_to_cart') }}</button>
                            <button
                                type="submit"
                                formaction="{{ route('checkout.buy-now', $product) }}"
                                class="btn btn-outline-primary flex-fill"
                                @disabled($product->stock_quantity < 1)
                            >
                                {{ __('store.buy_now') }}
                            </button>
                        </div>
                    </form>
                </div>

                <div class="feature-list mb-4">
                    <div class="feature-list-item">
                        <i class="bi bi-shield-check fs-5"></i>
                        <div>
                            <div class="fw-semibold">{{ __('store.catalog_hero_verified') }}</div>
                            <div class="small text-secondary">{{ __('store.detail_verified_note') }}</div>
                        </div>
                    </div>
                    <div class="feature-list-item">
                        <i class="bi bi-lock fs-5"></i>
                        <div>
                            <div class="fw-semibold">{{ __('store.catalog_hero_secure') }}</div>
                            <div class="small text-secondary">{{ __('store.detail_secure_note') }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-3">
                    <a href="{{ route('storefront.products.index') }}" class="btn btn-outline-primary">{{ __('store.back_to_catalog') }}</a>
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">{{ __('store.edit_in_admin') }}</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

<section class="page-reveal mb-5">
    <div class="store-card p-4 p-lg-5 mb-4">
        <div class="section-heading mb-4">
            <div>
                <h2 class="h3 mb-1">{{ __('store.reviews_title') }}</h2>
                <p class="muted-copy">{{ __('store.reviews_subtitle') }}</p>
            </div>
            <div class="text-md-end">
                @include('storefront._rating-stars', [
                    'stars' => $product->rating_stars,
                    'value' => $product->average_rating,
                    'count' => $product->review_count,
                    'showValue' => $product->review_count > 0,
                ])
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="d-flex flex-column gap-3">
                    @forelse ($product->approvedReviews as $review)
                        <article class="review-card">
                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-2">
                                <div>
                                    <div class="fw-semibold">{{ $review->reviewer_name }}</div>
                                    <div class="small text-secondary">{{ $review->created_at->format('d/m/Y') }}</div>
                                </div>
                                @include('storefront._rating-stars', [
                                    'stars' => collect(range(1, 5))->map(fn (int $star) => $star <= $review->rating ? 'full' : 'empty'),
                                    'value' => $review->rating,
                                    'showValue' => false,
                                ])
                            </div>
                            <p class="mb-0 text-dark">{{ $review->comment }}</p>
                        </article>
                    @empty
                        <div class="store-card p-4 text-center text-secondary">
                            <div class="empty-illustration"><i class="bi bi-chat-square-heart"></i></div>
                            {{ __('store.no_reviews_yet') }}
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-5">
                <div class="review-form-card p-4">
                    <h3 class="h5 mb-2">{{ __('store.leave_review_title') }}</h3>
                    <p class="muted-copy mb-3">{{ __('store.leave_review_note') }}</p>

                    @auth
                        @if (auth()->user()->isCustomer())
                            @if ($userReview && $userReview->status === \App\Models\ProductReview::STATUS_REJECTED)
                                <div class="helper-note mb-3">{{ __('store.review_rejected_notice') }}</div>
                            @endif

                            <form method="POST" action="{{ route('storefront.products.reviews.store', $product) }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label" for="rating">{{ __('common.rating') }}</label>
                                    <select id="rating" name="rating" class="form-select @error('rating') is-invalid @enderror" required>
                                        <option value="">{{ __('store.select_rating') }}</option>
                                        @foreach ([5, 4, 3, 2, 1] as $ratingOption)
                                            <option value="{{ $ratingOption }}" @selected(old('rating', $userReview?->rating) == $ratingOption)>{{ __('store.rating_option', ['rating' => $ratingOption]) }}</option>
                                        @endforeach
                                    </select>
                                    @error('rating')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="comment">{{ __('common.comment') }}</label>
                                    <textarea id="comment" name="comment" rows="5" maxlength="1000" class="form-control @error('comment') is-invalid @enderror" placeholder="{{ __('store.comment_placeholder') }}" required>{{ old('comment', $userReview?->comment) }}</textarea>
                                    @error('comment')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary w-100">{{ $userReview ? __('store.update_review') : __('store.submit_review') }}</button>
                            </form>
                        @else
                            <div class="helper-note">{{ __('store.review_customer_only') }}</div>
                        @endif
                    @else
                        <div class="helper-note mb-3">{{ __('store.login_to_review') }}</div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-primary">{{ __('common.login') }}</a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">{{ __('common.register') }}</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="store-card p-4 p-lg-5">
        <div class="section-heading mb-4">
            <div>
                <h2 class="h3 mb-1">{{ __('store.product_benefits_title') }}</h2>
                <p class="muted-copy">{{ __('store.product_benefits_note') }}</p>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-4 stagger-item" data-delay="0">
                <div class="category-tile h-100">
                    <span class="category-icon soft-accent-1"><i class="bi bi-truck"></i></span>
                    <div class="fw-semibold mb-1">{{ __('store.benefit_fast_delivery_title') }}</div>
                    <div class="small muted-copy">{{ __('store.benefit_fast_delivery_text') }}</div>
                </div>
            </div>
            <div class="col-md-4 stagger-item" data-delay="90">
                <div class="category-tile h-100">
                    <span class="category-icon soft-accent-2"><i class="bi bi-patch-check"></i></span>
                    <div class="fw-semibold mb-1">{{ __('store.benefit_curated_title') }}</div>
                    <div class="small muted-copy">{{ __('store.benefit_curated_text') }}</div>
                </div>
            </div>
            <div class="col-md-4 stagger-item" data-delay="180">
                <div class="category-tile h-100">
                    <span class="category-icon soft-accent-4"><i class="bi bi-lock"></i></span>
                    <div class="fw-semibold mb-1">{{ __('store.benefit_trust_title') }}</div>
                    <div class="small muted-copy">{{ __('store.benefit_trust_text') }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="page-reveal">
    <div class="section-heading">
        <div>
            <h2 class="h3 mb-1">{{ __('store.similar_products') }}</h2>
            <p class="muted-copy mb-0">{{ __('store.similar_products_description') }}</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($relatedProducts as $relatedProduct)
            <div class="col-md-6 col-xl-3 stagger-item" data-delay="{{ $loop->index * 70 }}">
                @include('storefront._product-card', ['product' => $relatedProduct])
            </div>
        @empty
            <div class="col-12">
                <div class="store-card p-4 text-center text-secondary">
                    <div class="empty-illustration"><i class="bi bi-heart"></i></div>
                    {{ __('store.no_similar_products') }}
                </div>
            </div>
        @endforelse
    </div>
</section>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-gallery-thumb]').forEach((button) => {
            button.addEventListener('click', () => {
                const primaryImage = document.getElementById('primaryProductImage');

                if (! primaryImage) {
                    return;
                }

                primaryImage.src = button.dataset.imageUrl;
                primaryImage.alt = button.dataset.imageAlt;

                document.querySelectorAll('[data-gallery-thumb]').forEach((thumb) => {
                    thumb.classList.remove('active');
                });

                button.classList.add('active');
            });
        });
    </script>
@endpush
