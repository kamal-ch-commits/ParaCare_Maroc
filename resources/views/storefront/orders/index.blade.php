@extends('layouts.storefront')

@section('title', __('store.my_orders_title'))

@section('content')
<section class="hero-panel p-4 p-lg-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-lg-8">
            <span class="eyebrow mb-3"><i class="bi bi-bag-heart"></i> {{ __('store.my_orders') }}</span>
            <h1 class="display-6 fw-bold mb-2">{{ __('store.my_orders_heading') }}</h1>
            <p class="muted-copy mb-0">{{ __('store.my_orders_note') }}</p>
        </div>
        <div class="col-lg-4">
            <div class="soft-panel p-4">
                <div class="small text-secondary mb-1">{{ __('common.items') }}</div>
                <div class="h4 mb-0">{{ $orders->total() }}</div>
            </div>
        </div>
    </div>
</section>

<section class="d-flex flex-column gap-3">
    @forelse ($orders as $order)
        <article class="order-history-card p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-3">
                <div>
                    <div class="eyebrow mb-2"><i class="bi bi-receipt"></i> {{ __('store.order_reference_short', ['id' => $order->id]) }}</div>
                    <h2 class="h5 mb-1">{{ __('store.order_placed_on', ['date' => $order->created_at->format('d/m/Y H:i')]) }}</h2>
                    <p class="muted-copy mb-0">{{ __('store.order_items_count', ['count' => $order->items->count()]) }}</p>
                </div>
                <div class="d-flex flex-column align-items-lg-end gap-2">
                    <span class="status-badge status-badge-cool">{{ __('store.payment_method_' . $order->payment_method) }}</span>
                    <span class="status-badge {{ in_array($order->payment_status, ['paid', 'cash_on_delivery'], true) ? 'status-badge-soft' : 'status-badge-warm' }}">
                        {{ __('store.payment_status_' . $order->payment_status) }}
                    </span>
                </div>
            </div>

            <div class="order-meta-grid mb-3">
                <div class="checkout-note-card">
                    <div class="small text-secondary mb-1">{{ __('common.total') }}</div>
                    <div class="fw-semibold">{{ number_format($order->total_amount, 2) }} DH</div>
                </div>
                <div class="checkout-note-card">
                    <div class="small text-secondary mb-1">{{ __('common.status') }}</div>
                    <div class="fw-semibold">{{ __('store.status_confirmed') }}</div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-primary">{{ __('store.view_order_details') }}</a>
                <a href="{{ route('storefront.products.index') }}" class="btn btn-outline-primary">{{ __('store.back_to_store') }}</a>
            </div>
        </article>
    @empty
        <div class="summary-card p-4 p-lg-5 text-center">
            <h2 class="h4 mb-2">{{ __('store.no_orders_title') }}</h2>
            <p class="muted-copy mb-4">{{ __('store.no_orders_note') }}</p>
            <a href="{{ route('storefront.products.index') }}" class="btn btn-primary">{{ __('store.explore_products') }}</a>
        </div>
    @endforelse
</section>

@if ($orders->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
@endif
@endsection
