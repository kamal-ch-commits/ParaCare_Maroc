@extends('layouts.storefront')

@section('title', __('store.products_title'))

@section('content')
@php
    $wellnessCards = [
        [
            'label' => __('store.catalog_hero_skincare'),
            'description' => __('store.catalog_hero_skincare_note'),
            'icon' => 'bi bi-droplet-half',
            'accent' => 'dashboard-accent-teal',
        ],
        [
            'label' => __('store.catalog_hero_baby'),
            'description' => __('store.catalog_hero_baby_note'),
            'icon' => 'bi bi-balloon-heart',
            'accent' => 'dashboard-accent-beige',
        ],
        [
            'label' => __('store.catalog_hero_supplements'),
            'description' => __('store.catalog_hero_supplements_note'),
            'icon' => 'bi bi-capsule-pill',
            'accent' => 'dashboard-accent-green',
        ],
        [
            'label' => __('store.catalog_hero_pharmacy'),
            'description' => __('store.catalog_hero_pharmacy_note'),
            'icon' => 'bi bi-shield-plus',
            'accent' => 'dashboard-accent-cream',
        ],
        [
            'label' => __('store.catalog_hero_protection'),
            'description' => __('store.catalog_hero_protection_note'),
            'icon' => 'bi bi-sun',
            'accent' => 'dashboard-accent-orange',
        ],
        [
            'label' => __('store.catalog_hero_natural'),
            'description' => __('store.catalog_hero_natural_note'),
            'icon' => 'bi bi-flower1',
            'accent' => 'dashboard-accent-green',
        ],
        [
            'label' => __('store.catalog_hero_health'),
            'description' => __('store.catalog_hero_health_note'),
            'icon' => 'bi bi-heart-pulse',
            'accent' => 'dashboard-accent-teal',
        ],
        [
            'label' => __('store.catalog_hero_secure'),
            'description' => __('store.catalog_hero_secure_note'),
            'icon' => 'bi bi-shield-lock',
            'accent' => 'dashboard-accent-cream',
        ],
    ];
@endphp
<section class="hero-panel p-4 p-lg-5 mb-4 page-reveal">
    <div class="row g-4 g-xl-5 align-items-stretch">
        <div class="col-lg-5">
            <div class="products-hero-copy">
                <span class="eyebrow mb-3"><i class="bi bi-grid"></i> {{ __('store.catalog_public') }}</span>
                <h1 class="display-6 fw-bold mb-2">{{ __('store.products_heading') }}</h1>
                <p class="muted-copy mb-4">{{ __('store.products_description') }}</p>

                <div class="catalog-hero-info-strip">
                    <div class="catalog-info-chip">
                        <i class="bi bi-heart-pulse"></i>
                        <span>{{ __('store.catalog_hero_wellness') }}</span>
                    </div>
                    <div class="catalog-info-chip">
                        <i class="bi bi-flower1"></i>
                        <span>{{ __('store.catalog_hero_natural') }}</span>
                    </div>
                    <div class="catalog-info-chip">
                        <i class="bi bi-hospital"></i>
                        <span>{{ __('store.catalog_hero_health') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="products-hero-side">
                <div class="catalog-dashboard">
                    <div class="catalog-dashboard-header">
                        <div>
                            <div class="catalog-dashboard-kicker">{{ __('store.catalog_public') }}</div>
                            <h2 class="h4 mb-1">{{ __('store.catalog_dashboard_title') }}</h2>
                            <p class="catalog-dashboard-note mb-0">{{ __('store.catalog_dashboard_note') }}</p>
                        </div>
                        <div class="catalog-dashboard-meta">
                            <span class="catalog-pill dashboard-summary-pill">
                                <i class="bi bi-grid-3x3-gap"></i>
                                {{ __('store.catalog_results_label', ['count' => $products->total()]) }}
                            </span>
                            <span class="dashboard-trust-badge">
                                <i class="bi bi-patch-check-fill"></i>
                                {{ __('store.catalog_hero_verified') }}
                            </span>
                        </div>
                    </div>

                    <div class="catalog-dashboard-grid">
                        @foreach ($wellnessCards as $card)
                            <article class="catalog-dashboard-card">
                                <span class="catalog-dashboard-icon {{ $card['accent'] }}">
                                    <i class="{{ $card['icon'] }}"></i>
                                </span>
                                <div>
                                    <div class="catalog-dashboard-card-title">{{ $card['label'] }}</div>
                                    <div class="catalog-dashboard-card-copy">{{ $card['description'] }}</div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>

                <form method="GET" action="{{ route('storefront.products.index') }}" class="filter-shell p-3 p-lg-4">
                    <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
                        <div class="fw-semibold">{{ __('store.catalog_filters_title') }}</div>
                        <div class="small text-secondary">{{ __('store.catalog_filters_note') }}</div>
                    </div>
                    <div class="row g-3 filters-grid">
                        <div class="col-12">
                            <label class="form-label" for="search">{{ __('store.search_product') }}</label>
                            <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="{{ __('store.search_product') }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="category">{{ __('common.category') }}</label>
                            <select id="category" name="category" class="form-select">
                                <option value="">{{ __('store.all_categories') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected($selectedCategory === $category->id)>{{ $category->translated_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="min_price">{{ __('store.min_price_label') }}</label>
                            <input id="min_price" type="number" step="0.01" min="0" name="min_price" value="{{ $minPrice }}" class="form-control" placeholder="{{ __('store.min_price_placeholder') }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="max_price">{{ __('store.max_price_label') }}</label>
                            <input id="max_price" type="number" step="0.01" min="0" name="max_price" value="{{ $maxPrice }}" class="form-control" placeholder="{{ __('store.max_price_placeholder') }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="stock_status">{{ __('store.stock_status_label') }}</label>
                            <select id="stock_status" name="stock_status" class="form-select">
                                <option value="all" @selected($stockStatus === 'all')>{{ __('store.stock_status_all') }}</option>
                                <option value="in_stock" @selected($stockStatus === 'in_stock')>{{ __('store.stock_status_in') }}</option>
                                <option value="out_of_stock" @selected($stockStatus === 'out_of_stock')>{{ __('store.stock_status_out') }}</option>
                            </select>
                        </div>
                        <div class="col-sm-8">
                            <label class="form-label" for="sort">{{ __('store.sort_by_label') }}</label>
                            <select id="sort" name="sort" class="form-select">
                                <option value="newest" @selected($sort === 'newest')>{{ __('store.sort_newest') }}</option>
                                <option value="price_asc" @selected($sort === 'price_asc')>{{ __('store.sort_price_low_high') }}</option>
                                <option value="price_desc" @selected($sort === 'price_desc')>{{ __('store.sort_price_high_low') }}</option>
                                <option value="best_rated" @selected($sort === 'best_rated')>{{ __('store.sort_best_rated') }}</option>
                                <option value="most_reviewed" @selected($sort === 'most_reviewed')>{{ __('store.sort_most_reviewed') }}</option>
                                <option value="name_asc" @selected($sort === 'name_asc')>{{ __('store.sort_name_asc') }}</option>
                            </select>
                        </div>
                        <div class="col-sm-4 d-grid align-self-end">
                            <button class="btn btn-primary" type="submit">{{ __('common.filter') }}</button>
                        </div>
                        <div class="col-12 d-grid">
                            <a href="{{ route('storefront.products.index') }}" class="btn btn-outline-primary">{{ __('store.reset_filters') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="page-reveal">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div class="product-meta-stack">
            <span class="catalog-pill"><i class="bi bi-grid-3x3-gap"></i> {{ __('store.catalog_results_label', ['count' => $products->total()]) }}</span>
            @if ($selectedCategory)
                <span class="catalog-pill"><i class="bi bi-funnel"></i> {{ __('store.catalog_filtered') }}</span>
            @endif
            @if (filled($search))
                <span class="catalog-pill"><i class="bi bi-search"></i> {{ $search }}</span>
            @endif
            @if ($minPrice !== null || $maxPrice !== null)
                <span class="catalog-pill"><i class="bi bi-cash-stack"></i> {{ __('store.price_range_active') }}</span>
            @endif
        </div>
    </div>

    <div class="row g-4">
        @forelse ($products as $product)
            <div class="col-md-6 col-xl-4 stagger-item" data-delay="{{ $loop->index * 65 }}">
                @include('storefront._product-card', ['product' => $product])
            </div>
        @empty
            <div class="col-12">
                <div class="store-card p-5 text-center text-secondary">
                    <div class="empty-illustration"><i class="bi bi-search-heart"></i></div>
                    <h2 class="h4 mb-2 text-dark">{{ __('store.empty_search_title') }}</h2>
                    <p class="muted-copy mb-3">{{ __('store.no_products_for_search') }}</p>
                    <p class="small text-secondary mb-0">{{ __('store.empty_search_help') }}</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</section>
@endsection
