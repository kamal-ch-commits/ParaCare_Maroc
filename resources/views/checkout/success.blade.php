@extends('layouts.storefront')

@section('title', __('store.order_success_title'))

@section('content')
<section class="hero-panel p-4 p-lg-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-lg-8">
            <span class="eyebrow mb-3"><i class="bi bi-check2-circle"></i> {{ __('store.order_recorded') }}</span>
            <h1 class="display-6 fw-bold mb-2">{{ __('store.thank_you_order', ['name' => $order['customer_name']]) }}</h1>
            <p class="muted-copy mb-0">{{ __('store.internal_reference', ['id' => $order['id']]) }}</p>
        </div>
        <div class="col-lg-4">
            <div class="soft-panel p-4">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="small text-secondary mb-1">{{ __('common.status') }}</div>
                        <div class="fw-semibold">{{ __('store.status_confirmed') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-secondary mb-1">{{ __('common.total') }}</div>
                        <div class="fw-semibold">{{ number_format($order['total_amount'], 2) }} DH</div>
                    </div>
                    <div class="col-12">
                        <div class="small text-secondary mb-1">{{ __('store.payment_method_title') }}</div>
                        <div class="fw-semibold">{{ __('store.payment_method_' . $order['payment_method']) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="row g-4">
    <div class="col-xl-7">
        <div class="summary-card p-4 p-lg-5">
            <h2 class="h4 mb-4">{{ __('store.ordered_products') }}</h2>
            <div class="d-flex flex-column gap-3">
                @foreach ($order['items'] as $item)
                    <div class="order-line-card d-flex align-items-center gap-3">
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->translated_name }}" class="line-item-image">
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $item->product->translated_name }}</div>
                            <div class="small text-secondary">{{ $item->quantity }} x {{ number_format($item->unit_price, 2) }} DH</div>
                        </div>
                        <div class="fw-semibold">{{ number_format($item->subtotal, 2) }} DH</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="summary-card p-4 mb-3">
            <h2 class="h4 mb-3">{{ __('store.contact_details') }}</h2>
            <div class="summary-totals">
                <div><span class="muted-copy">{{ __('common.email') }}:</span> {{ $order['customer_email'] }}</div>
                <div><span class="muted-copy">{{ __('common.phone') }}:</span> {{ $order['customer_phone'] ?: __('common.not_provided') }}</div>
                <div><span class="muted-copy">{{ __('store.payment_method_title') }}:</span> {{ __('store.payment_method_' . $order['payment_method']) }}</div>
                <div><span class="muted-copy">{{ __('store.payment_status_title') }}:</span> {{ __('store.payment_status_' . $order['payment_status']) }}</div>
                @if (! empty($order['payment_reference']))
                    <div><span class="muted-copy">{{ __('store.payment_reference') }}:</span> {{ $order['payment_reference'] }}</div>
                @endif
            </div>
        </div>

        @if ($order['payment_method'] === 'bank_transfer')
            <div class="info-callout mb-3">
                <div class="fw-semibold mb-1">{{ __('store.bank_transfer_instructions_title') }}</div>
                <div class="small">{{ __('store.bank_transfer_instructions') }}</div>
            </div>
        @elseif (in_array($order['payment_method'], ['paypal', 'card'], true))
            <div class="info-callout mb-3">
                <div class="fw-semibold mb-1">{{ __('store.online_payment_placeholder_title') }}</div>
                <div class="small">{{ __('store.online_payment_placeholder') }}</div>
            </div>
        @endif

        <div class="checkout-note-card mb-3">
            <div class="fw-semibold mb-1">{{ __('store.catalog_hero_verified') }}</div>
            <div class="small">{{ __('store.success_support_note') }}</div>
        </div>

        <div class="d-grid gap-2">
            <a href="{{ route('storefront.products.index') }}" class="btn btn-primary">{{ __('store.back_to_store') }}</a>
            <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">{{ __('store.view_cart') }}</a>
            @auth
                <a href="{{ route('customer.orders.show', $order['id']) }}" class="btn btn-outline-primary">{{ __('store.view_order_details') }}</a>
            @endauth
        </div>
    </div>
</section>
@endsection
