@extends('layouts.app')

@section('title', __('admin.order_details_title'))

@section('content')
<div class="section-header">
    <div>
        <h1 class="h2 page-title mb-1">{{ __('admin.order_details_heading', ['id' => $order->id]) }}</h1>
        <p class="section-subtitle">{{ __('admin.orders_description') }}</p>
    </div>
    <div class="d-flex action-bar">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary">{{ __('common.orders') }}</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.total') }}</div><div class="h2 fw-bold mb-1">{{ number_format($order->total_amount, 2) }} DH</div><div class="text-secondary small">{{ __('admin.payment_summary_title') }}</div></div><span class="admin-metric-icon"><i class="bi bi-cash-stack"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.items') }}</div><div class="h2 fw-bold mb-1">{{ $order->items->sum('quantity') }}</div><div class="text-secondary small">{{ __('store.ordered_products') }}</div></div><span class="admin-metric-icon"><i class="bi bi-basket2"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.payment_method') }}</div><div class="h5 fw-bold mb-1">{{ __('store.payment_method_' . $order->payment_method) }}</div><div class="text-secondary small">{{ $order->created_at->format('d/m/Y H:i') }}</div></div><span class="admin-metric-icon"><i class="bi bi-credit-card"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.payment_status') }}</div><div class="h5 fw-bold mb-1">{{ __('store.payment_status_' . $order->payment_status) }}</div><div class="text-secondary small">{{ __('admin.update_payment_status') }}</div></div><span class="admin-metric-icon"><i class="bi bi-shield-check"></i></span></div></div></div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card content-card h-100">
            <div class="card-header bg-white fw-semibold">{{ __('store.ordered_products') }}</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('common.product') }}</th>
                            <th>{{ __('common.quantity') }}</th>
                            <th>{{ __('common.unit_price') }}</th>
                            <th>{{ __('common.subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->product->translated_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }} DH</td>
                                <td>{{ number_format($item->subtotal, 2) }} DH</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card content-card mb-4">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">{{ __('admin.payment_summary_title') }}</h2>
                <div class="d-flex flex-column gap-2">
                    <div><strong>{{ __('common.user') }}:</strong> {{ $order->user?->name ?? $order->customer_name }}</div>
                    <div><strong>{{ __('common.email') }}:</strong> {{ $order->customer_email }}</div>
                    <div><strong>{{ __('common.phone') }}:</strong> {{ $order->customer_phone ?: __('common.not_provided') }}</div>
                    <div><strong>{{ __('common.total') }}:</strong> {{ number_format($order->total_amount, 2) }} DH</div>
                    <div><strong>{{ __('admin.payment_method') }}:</strong> {{ __('store.payment_method_' . $order->payment_method) }}</div>
                    <div><strong>{{ __('admin.payment_status') }}:</strong> {{ __('store.payment_status_' . $order->payment_status) }}</div>
                    @if ($order->payment_reference)
                        <div><strong>{{ __('store.payment_reference') }}:</strong> {{ $order->payment_reference }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card content-card">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">{{ __('admin.update_payment_status') }}</h2>
                <form method="POST" action="{{ route('admin.orders.payment.update', $order) }}" class="d-flex flex-column gap-3">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="payment_status" class="form-label">{{ __('admin.payment_status') }}</label>
                        <select id="payment_status" name="payment_status" class="form-select">
                            @foreach ($paymentStatuses as $status)
                                <option value="{{ $status }}" @selected(old('payment_status', $order->payment_status) === $status)>{{ __('store.payment_status_' . $status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="payment_reference" class="form-label">{{ __('store.payment_reference') }}</label>
                        <input type="text" id="payment_reference" name="payment_reference" value="{{ old('payment_reference', $order->payment_reference) }}" class="form-control" placeholder="{{ __('admin.payment_reference_placeholder') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('admin.save_payment_status') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
