@extends('layouts.storefront')

@section('title', __('store.cart_title'))

@section('content')
<section class="hero-panel p-4 p-lg-5 mb-4">
    <div class="row g-4 align-items-end">
        <div class="col-lg-7">
            <span class="eyebrow mb-3"><i class="bi bi-bag-check"></i> {{ __('store.customer_cart') }}</span>
            <h1 class="display-6 fw-bold mb-2">{{ __('store.cart_heading') }}</h1>
            <p class="muted-copy mb-0">{{ __('store.cart_description') }}</p>
        </div>
        <div class="col-lg-5">
            <div class="soft-panel p-4">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="small text-secondary mb-1">{{ __('common.items') }}</div>
                        <div class="h4 mb-0">{{ $item_count }}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-secondary mb-1">{{ __('common.total') }}</div>
                        <div class="h4 mb-0">{{ number_format($total, 2) }} DH</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if ($is_empty)
    <section class="empty-state p-5 text-center">
        <div class="mx-auto" style="max-width: 520px;">
            <div class="empty-illustration"><i class="bi bi-cart-x"></i></div>
            <h2 class="h3 mb-2">{{ __('store.empty_cart_heading') }}</h2>
            <p class="muted-copy mb-4">{{ __('store.empty_cart_description') }}</p>
            <a href="{{ route('storefront.products.index') }}" class="btn btn-primary">{{ __('common.continue_shopping') }}</a>
        </div>
    </section>
@else
    <section class="row g-4">
        <div class="col-xl-8">
            <div class="cart-table-card p-3 p-lg-4">
                <div class="d-flex flex-column gap-3">
                    @foreach ($items as $item)
                        <div class="cart-item-card">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->translated_name }}" class="line-item-image">
                                        <div>
                                            <h2 class="h5 mb-1">{{ $item->product->translated_name }}</h2>
                                            <div class="small text-secondary mb-2">{{ $item->product->category?->translated_name ?? __('store.without_category') }}</div>
                                            <span class="mini-badge"><i class="bi bi-cash-stack"></i> {{ __('common.unit_price') }}: {{ number_format($item->unit_price, 2) }} DH</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex flex-column gap-2">
                                        <div class="small text-secondary">{{ __('common.quantity') }}</div>
                                        <div class="d-flex flex-wrap gap-2">
                                            <form method="POST" action="{{ route('cart.update', $item->product) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="quantity" value="{{ max(1, $item->quantity - 1) }}">
                                                <button type="submit" class="btn btn-outline-primary btn-sm" @disabled($item->quantity <= 1)>-</button>
                                            </form>
                                            <form method="POST" action="{{ route('cart.update', $item->product) }}" class="d-flex gap-2 align-items-center">
                                                @csrf
                                                @method('PATCH')
                                                <div class="quantity-stepper">
                                                    <input
                                                        type="number"
                                                        min="1"
                                                        max="{{ $item->product->stock_quantity }}"
                                                        name="quantity"
                                                        value="{{ $item->quantity }}"
                                                        class="quantity-field"
                                                    >
                                                </div>
                                                <button type="submit" class="btn btn-outline-primary btn-sm">{{ __('store.update_cart') }}</button>
                                            </form>
                                            <form method="POST" action="{{ route('cart.update', $item->product) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="quantity" value="{{ min($item->product->stock_quantity, $item->quantity + 1) }}">
                                                <button type="submit" class="btn btn-outline-primary btn-sm" @disabled($item->quantity >= $item->product->stock_quantity)>+</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-md-end">
                                    <div class="small text-secondary mb-1">{{ __('common.subtotal') }}</div>
                                    <div class="fw-bold mb-3">{{ number_format($item->subtotal, 2) }} DH</div>
                                    <form method="POST" action="{{ route('cart.destroy', $item->product) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">{{ __('common.remove') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="summary-card p-4 mb-3">
                <h2 class="h4 mb-3">{{ __('store.order_summary') }}</h2>
                <div class="summary-totals">
                    <div class="d-flex justify-content-between">
                        <span class="muted-copy">{{ __('store.different_products') }}</span>
                        <span>{{ $unique_count }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="muted-copy">{{ __('store.total_quantity') }}</span>
                        <span>{{ $item_count }}</span>
                    </div>
                    <div class="surface-divider"></div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">{{ __('store.total_to_confirm') }}</span>
                        <span class="h4 mb-0">{{ number_format($total, 2) }} DH</span>
                    </div>
                </div>
            </div>

            <div class="checkout-note-card mb-3">
                <div class="fw-semibold mb-1">{{ __('store.catalog_hero_secure') }}</div>
                <div class="small">{{ __('store.cart_support_note') }}</div>
            </div>

            <div class="d-grid gap-2">
                <a href="{{ route('checkout.create') }}" class="btn btn-primary">{{ __('store.proceed_to_checkout') }}</a>
                <a href="{{ route('storefront.products.index') }}" class="btn btn-outline-primary">{{ __('common.continue_shopping') }}</a>
                <form method="POST" action="{{ route('cart.clear') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">{{ __('store.clear_cart') }}</button>
                </form>
            </div>
        </div>
    </section>
@endif
@endsection
