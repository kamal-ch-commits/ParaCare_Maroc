@extends('layouts.storefront')

@section('title', __('store.checkout_title'))

@section('content')
<section class="hero-panel p-4 p-lg-5 mb-4">
    <div class="row g-4 align-items-end">
        <div class="col-lg-8">
            <span class="eyebrow mb-3"><i class="bi bi-credit-card-2-front"></i> {{ __('store.simple_checkout') }}</span>
            <h1 class="display-6 fw-bold mb-2">{{ $heading }}</h1>
            <p class="muted-copy mb-0">{{ $subheading }}</p>
        </div>
        <div class="col-lg-4">
            <div class="soft-panel p-4">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="small text-secondary mb-1">{{ __('common.items') }}</div>
                        <div class="h4 mb-0">{{ $summary['item_count'] }}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-secondary mb-1">{{ __('common.amount') }}</div>
                        <div class="h4 mb-0">{{ number_format($summary['total'], 2) }} DH</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="row g-4">
    <div class="col-xl-7">
        <div class="summary-card p-4 p-lg-5">
            <div class="section-heading mb-4">
                <div>
                    <h2 class="h4 mb-1">{{ __('store.customer_information') }}</h2>
                    <p class="muted-copy mb-0">{{ __('store.checkout_customer_note') }}</p>
                </div>
            </div>

            <form method="POST" action="{{ $checkoutAction }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="customer_name">{{ __('common.full_name') }}</label>
                        <input type="text" id="customer_name" name="customer_name" value="{{ $customer['name'] }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="customer_email">{{ __('common.email') }}</label>
                        <input type="email" id="customer_email" name="customer_email" value="{{ $customer['email'] }}" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="customer_phone">{{ __('common.phone') }}</label>
                        <input type="text" id="customer_phone" name="customer_phone" value="{{ $customer['phone'] }}" class="form-control" placeholder="{{ __('common.optional') }}">
                    </div>
                </div>

                <div class="section-heading mt-4 mb-3">
                    <div>
                        <h2 class="h5 mb-1">{{ __('store.payment_method_title') }}</h2>
                        <p class="muted-copy mb-0">{{ __('store.payment_method_note') }}</p>
                    </div>
                </div>

                <div class="payment-method-grid mb-4">
                    @foreach ($paymentMethods as $method)
                        <div class="payment-method-option">
                            <input
                                class="payment-method-input"
                                type="radio"
                                id="payment_method_{{ $method['value'] }}"
                                name="payment_method"
                                value="{{ $method['value'] }}"
                                @checked($selectedPaymentMethod === $method['value'])
                            >
                            <label class="payment-method-card" for="payment_method_{{ $method['value'] }}">
                                <div class="payment-method-head">
                                    <span class="payment-method-icon"><i class="bi bi-{{ $method['icon'] }}"></i></span>
                                    <span class="payment-method-check"><i class="bi bi-check-lg"></i></span>
                                </div>
                                <div class="payment-method-title">{{ $method['label'] }}</div>
                                <p class="payment-method-description">{{ $method['description'] }}</p>
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="checkout-note-card mt-4 mb-4">
                    <div class="fw-semibold mb-1">{{ __('store.catalog_hero_verified') }}</div>
                    <div class="small">{{ __('store.simple_checkout_note') }}</div>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('store.confirm_order') }}</button>
                    <a href="{{ $returnUrl }}" class="btn btn-outline-primary">{{ __('common.back') }}</a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="summary-card p-4">
            <h2 class="h4 mb-3">{{ __('store.order_summary') }}</h2>
            <div class="d-flex flex-column gap-3 mb-4">
                @foreach ($summary['items'] as $item)
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
            <div class="surface-divider"></div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-semibold">{{ __('common.total') }}</span>
                <span class="h4 mb-0">{{ number_format($summary['total'], 2) }} DH</span>
            </div>
            <div class="info-callout mt-4">
                <div class="fw-semibold mb-1">{{ __('store.payment_method_safe_notice_title') }}</div>
                <div class="small">{{ __('store.payment_method_safe_notice') }}</div>
            </div>
        </div>
    </div>
</section>
@endsection
