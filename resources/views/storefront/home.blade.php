@extends('layouts.storefront')

@section('title', __('store.home_title'))

@section('content')
@php
    $categoryAccentClasses = ['soft-accent-1', 'soft-accent-2', 'soft-accent-3', 'soft-accent-4'];
    $categoryIcons = [
        'Bebe' => 'bi bi-balloon-heart',
        'Bébé & maman' => 'bi bi-balloon-heart',
        'Complements' => 'bi bi-capsule-pill',
        'Compléments alimentaires' => 'bi bi-capsule-pill',
        'Hygiene' => 'bi bi-droplet',
        'Hygiène & santé quotidienne' => 'bi bi-droplet',
        'Protection solaire' => 'bi bi-sun',
        'Soins visage' => 'bi bi-stars',
        'Soins du corps' => 'bi bi-flower1',
        'Cheveux & soins capillaires' => 'bi bi-scissors',
        'Dermocosmétique' => 'bi bi-shield-plus',
        'Produits bio & naturels' => 'bi bi-leaf',
        'Materiel medical' => 'bi bi-heart-pulse',
        'Matériel médical' => 'bi bi-heart-pulse',
    ];
    $heroStats = [
        ['icon' => 'bi bi-shield-check', 'label' => __('store.hero_trust_quality'), 'value' => __('store.catalog_hero_verified')],
        ['icon' => 'bi bi-heart-pulse', 'label' => __('store.hero_trust_wellness'), 'value' => __('store.catalog_hero_health')],
        ['icon' => 'bi bi-box-seam', 'label' => __('store.hero_trust_catalog'), 'value' => __('store.catalog_dashboard_title')],
    ];
@endphp

<section class="hero-panel p-4 p-lg-5 mb-5 page-reveal">
    <div class="row align-items-center g-4 g-xl-5 position-relative">
        <div class="col-lg-6">
            <span class="eyebrow mb-3"><i class="bi bi-stars"></i> {{ __('store.modern_store') }}</span>
            <h1 class="display-4 fw-bold mb-3">{{ __('store.home_heading') }}</h1>
            <p class="lead muted-copy mb-4">{{ __('store.home_description') }}</p>
            <div class="d-flex flex-column flex-sm-row gap-3">
                <a href="{{ route('storefront.products.index') }}" class="btn btn-primary">{{ __('store.explore_products') }}</a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">{{ __('store.create_customer_account') }}</a>
                @else
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">{{ __('store.open_admin_dashboard') }}</a>
                    @endif
                @endguest
            </div>
            <div class="d-flex flex-wrap gap-2 mt-4 mb-4">
                <span class="catalog-pill"><i class="bi bi-shield-check"></i> {{ __('store.hero_trust_quality') }}</span>
                <span class="catalog-pill"><i class="bi bi-heart-pulse"></i> {{ __('store.hero_trust_wellness') }}</span>
                <span class="catalog-pill"><i class="bi bi-box-seam"></i> {{ __('store.hero_trust_catalog') }}</span>
            </div>
            <div class="row g-3">
                @foreach ($heroStats as $stat)
                    <div class="col-sm-4">
                        <div class="stat-tile h-100">
                            <span class="stat-tile-icon"><i class="{{ $stat['icon'] }}"></i></span>
                            <div>
                                <div class="small text-secondary mb-1">{{ $stat['label'] }}</div>
                                <div class="fw-semibold">{{ $stat['value'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-lg-6">
            <div class="hero-showcase-card hero-showcase-card-media h-100">
                <div class="hero-media-stage">
                    <span class="hero-media-badge hero-media-badge-top">
                        <i class="bi bi-stars"></i> {{ __('store.catalog_hero_verified') }}
                    </span>
                    <img src="{{ asset('images/cosmetic.gif') }}" alt="{{ __('store.home_heading') }}" class="hero-media-gif">
                    <span class="hero-media-badge hero-media-badge-bottom">
                        <i class="bi bi-grid"></i> {{ $categories->count() }} {{ __('store.categories_available') }}
                    </span>
                </div>
                <div class="hero-media-footer">
                    <span class="hero-media-chip"><i class="bi bi-heart-pulse"></i> {{ __('store.hero_trust_wellness') }}</span>
                    <span class="hero-media-chip"><i class="bi bi-box-seam"></i> {{ __('store.hero_trust_catalog') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-5 page-reveal" id="home-categories">
    <div class="section-heading">
        <div>
            <h2 class="h3 mb-1">{{ __('store.categories_available') }}</h2>
            <p class="muted-copy mb-0">{{ __('store.hero_categories_note') }}</p>
        </div>
        <a href="{{ route('storefront.products.index') }}" class="btn btn-outline-primary">{{ __('store.view_full_catalog') }}</a>
    </div>

    <div class="row g-4">
        @forelse ($categories->take(8) as $category)
            <div class="col-md-6 col-xl-3 stagger-item" data-delay="{{ $loop->index * 70 }}">
                <div class="category-tile h-100">
                    <span class="category-icon {{ $categoryAccentClasses[$loop->index % count($categoryAccentClasses)] }}">
                        <i class="{{ $categoryIcons[$category->getRawOriginal('name')] ?? 'bi bi-plus-circle' }}"></i>
                    </span>
                    <div class="fw-semibold mb-1">{{ $category->translated_name }}</div>
                    <div class="small text-secondary mb-2">{{ __('store.products_count', ['count' => $category->products_count]) }}</div>
                    <div class="small muted-copy">{{ \Illuminate\Support\Str::limit($category->translated_description, 88) }}</div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="store-card p-4 text-center text-secondary">
                    <div class="empty-illustration"><i class="bi bi-grid"></i></div>
                    {{ __('store.no_categories') }}
                </div>
            </div>
        @endforelse
    </div>
</section>

<section class="mb-4 page-reveal">
    <div class="section-heading">
        <div>
            <h2 class="h3 mb-1">{{ __('store.recent_products') }}</h2>
            <p class="muted-copy mb-0">{{ __('store.recent_products_description') }}</p>
        </div>
        <a href="{{ route('storefront.products.index') }}" class="btn btn-outline-primary">{{ __('store.view_full_catalog') }}</a>
    </div>

    <div class="row g-4">
        @forelse ($featuredProducts as $product)
            <div class="col-md-6 col-xl-3 stagger-item" data-delay="{{ $loop->index * 70 }}">
                @include('storefront._product-card', ['product' => $product])
            </div>
        @empty
            <div class="col-12">
                <div class="store-card p-4 text-center text-secondary">
                    <div class="empty-illustration"><i class="bi bi-bag-heart"></i></div>
                    {{ __('store.no_products_in_stock') }}
                </div>
            </div>
        @endforelse
    </div>
</section>
@endsection
