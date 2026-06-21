@extends('layouts.storefront')

@section('title', __('store.order_details_title'))

@section('content')
<section class="hero-panel p-4 p-lg-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-lg-8">
            <span class="eyebrow mb-3"><i class="bi bi-receipt-cutoff"></i> {{ __('store.order_reference_short', ['id' => $order->id]) }}</span>
            <h1 class="display-6 fw-bold mb-2">{{ __('store.order_details_heading') }}</h1>
            <p class="muted-copy mb-0">{{ __('store.order_placed_on', ['date' => $order->created_at->format('d/m/Y H:i')]) }}</p>
        </div>
        <div class="col-lg-4">
            <div class="soft-panel p-4">
                <div class="small text-secondary mb-1">{{ __('common.total') }}</div>
                <div class="h4 mb-0">{{ number_format($order->total_amount, 2) }} DH</div>
            </div>
        </div>
    </div>
</section>

<section class="row g-4">
    <div class="col-xl-7">
        <div class="summary-card p-4 p-lg-5">
            <h2 class="h4 mb-4">{{ __('store.ordered_products') }}</h2>
            <div class="d-flex flex-column gap-3">
                @foreach ($order->items as $item)
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
            <h2 class="h4 mb-3">{{ __('store.payment_summary_title') }}</h2>
            <div class="summary-totals">
                <div><span class="muted-copy">{{ __('store.payment_method_title') }}:</span> {{ __('store.payment_method_' . $order->payment_method) }}</div>
                <div><span class="muted-copy">{{ __('store.payment_status_title') }}:</span> {{ __('store.payment_status_' . $order->payment_status) }}</div>
                <div><span class="muted-copy">{{ __('common.email') }}:</span> {{ $order->customer_email }}</div>
                <div><span class="muted-copy">{{ __('common.phone') }}:</span> {{ $order->customer_phone ?: __('common.not_provided') }}</div>
                @if ($order->payment_reference)
                    <div><span class="muted-copy">{{ __('store.payment_reference') }}:</span> {{ $order->payment_reference }}</div>
                @endif
            </div>
        </div>

        @if ($order->payment_method === 'bank_transfer')
            <div class="info-callout mb-3">
                <div class="fw-semibold mb-1">{{ __('store.bank_transfer_instructions_title') }}</div>
                <div class="small">{{ __('store.bank_transfer_instructions') }}</div>
            </div>
        @elseif (in_array($order->payment_method, ['paypal', 'card'], true))
            <div class="info-callout mb-3">
                <div class="fw-semibold mb-1">{{ __('store.online_payment_placeholder_title') }}</div>
                <div class="small">{{ __('store.online_payment_placeholder') }}</div>
            </div>
        @endif

        <div class="d-grid gap-2">
            <a href="{{ route('customer.orders.index') }}" class="btn btn-primary">{{ __('store.back_to_orders') }}</a>
            <a href="{{ route('storefront.products.index') }}" class="btn btn-outline-primary">{{ __('store.back_to_store') }}</a>
        </div>
    </div>
</section>
@endsection
