@extends('layouts.app')

@section('title', __('admin.orders_title'))

@section('content')
@php
    $statusBadgeClasses = [
        'pending' => 'badge-soft-warning',
        'awaiting_transfer' => 'badge-soft-info',
        'paid' => 'badge-soft-success',
        'failed' => 'badge-soft-danger',
        'refunded' => 'badge-soft-info',
        'cash_on_delivery' => 'badge-soft-warning',
    ];

    $methodIcons = [
        'cash_on_delivery' => 'bi bi-cash-coin',
        'bank_transfer' => 'bi bi-bank',
        'paypal' => 'bi bi-paypal',
        'card' => 'bi bi-credit-card',
    ];
@endphp

<style>
    .orders-metric-card {
        position: relative;
        min-height: 150px;
        padding: 1.2rem;
        overflow: hidden;
    }

    .orders-metric-card::after {
        content: '';
        position: absolute;
        width: 120px;
        height: 120px;
        right: -44px;
        bottom: -52px;
        border-radius: 50%;
        background: rgba(15, 118, 110, 0.10);
    }

    .orders-metric-icon {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #0f766e;
        background: #d9f0eb;
        font-size: 1.25rem;
    }

    .orders-progress {
        height: .55rem;
        border-radius: 999px;
        background: #e5edf4;
        overflow: hidden;
    }

    .orders-progress span {
        display: block;
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
    }

    .orders-list-row {
        border: 1px solid rgba(219, 229, 239, 0.9);
        border-radius: 18px;
        padding: 1rem;
        background: #fff;
    }

    .orders-customer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 700;
    }
</style>

<div class="section-header">
    <div>
        <h1 class="h2 page-title mb-1">{{ __('admin.orders_title') }}</h1>
        <p class="section-subtitle">{{ __('admin.orders_description') }}</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('storefront.home') }}" class="btn btn-outline-primary">
            <i class="bi bi-shop me-1"></i> {{ __('admin.view_store') }}
        </a>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">
            <i class="bi bi-speedometer2 me-1"></i> {{ __('common.dashboard') }}
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card orders-metric-card h-100">
            <div class="d-flex justify-content-between align-items-start position-relative">
                <div>
                    <div class="text-secondary small fw-semibold mb-2">{{ __('common.orders') }}</div>
                    <div class="h2 fw-bold mb-1">{{ number_format($summary['total_orders']) }}</div>
                    <div class="text-secondary small">{{ __('admin.orders_count_description') }}</div>
                </div>
                <span class="orders-metric-icon"><i class="bi bi-bag-check"></i></span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card orders-metric-card h-100">
            <div class="d-flex justify-content-between align-items-start position-relative">
                <div>
                    <div class="text-secondary small fw-semibold mb-2">{{ __('admin.total_sales') }}</div>
                    <div class="h2 fw-bold mb-1">{{ number_format($summary['total_revenue'], 2) }} DH</div>
                    <div class="text-secondary small">{{ __('admin.last_30_days_revenue', ['amount' => number_format($summary['last_30_days_revenue'], 2)]) }}</div>
                </div>
                <span class="orders-metric-icon"><i class="bi bi-graph-up-arrow"></i></span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card orders-metric-card h-100">
            <div class="d-flex justify-content-between align-items-start position-relative">
                <div>
                    <div class="text-secondary small fw-semibold mb-2">{{ __('admin.pending_payments') }}</div>
                    <div class="h2 fw-bold mb-1">{{ number_format($summary['pending_payments']) }}</div>
                    <div class="text-secondary small">{{ __('admin.pending_payments_description') }}</div>
                </div>
                <span class="orders-metric-icon"><i class="bi bi-hourglass-split"></i></span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card orders-metric-card h-100">
            <div class="d-flex justify-content-between align-items-start position-relative">
                <div>
                    <div class="text-secondary small fw-semibold mb-2">{{ __('admin.average_order_value') }}</div>
                    <div class="h2 fw-bold mb-1">{{ number_format($summary['average_order_value'], 2) }} DH</div>
                    <div class="text-secondary small">{{ __('admin.today_orders_count', ['count' => $summary['today_orders']]) }}</div>
                </div>
                <span class="orders-metric-icon"><i class="bi bi-calculator"></i></span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card content-card h-100">
            <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                <span>{{ __('admin.payment_status') }}</span>
                <span class="badge badge-soft-info">{{ number_format($summary['total_orders']) }} {{ __('common.orders') }}</span>
            </div>
            <div class="card-body d-flex flex-column gap-3">
                @foreach ($paymentStatuses as $status)
                    @php
                        $statusData = $statusBreakdown->get($status);
                        $statusCount = (int) ($statusData?->orders_count ?? 0);
                        $statusTotal = (float) ($statusData?->total_amount ?? 0);
                        $statusPercent = $summary['total_orders'] > 0 ? round(($statusCount / $summary['total_orders']) * 100) : 0;
                    @endphp
                    <div>
                        <div class="d-flex justify-content-between gap-3 mb-2">
                            <div>
                                <span class="badge {{ $statusBadgeClasses[$status] ?? 'badge-soft-info' }}">{{ __('store.payment_status_' . $status) }}</span>
                                <span class="text-secondary small ms-2">{{ $statusCount }} {{ __('common.orders') }}</span>
                            </div>
                            <div class="fw-semibold">{{ number_format($statusTotal, 2) }} DH</div>
                        </div>
                        <div class="orders-progress" aria-hidden="true"><span style="width: {{ $statusPercent }}%"></span></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card content-card h-100">
            <div class="card-header bg-white fw-semibold">{{ __('admin.payment_method') }}</div>
            <div class="card-body d-flex flex-column gap-3">
                @foreach ($paymentMethods as $method)
                    @php
                        $methodData = $methodBreakdown->get($method);
                        $methodCount = (int) ($methodData?->orders_count ?? 0);
                        $methodTotal = (float) ($methodData?->total_amount ?? 0);
                    @endphp
                    <div class="orders-list-row d-flex justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="orders-metric-icon"><i class="{{ $methodIcons[$method] ?? 'bi bi-wallet2' }}"></i></span>
                            <div>
                                <div class="fw-semibold">{{ __('store.payment_method_' . $method) }}</div>
                                <div class="text-secondary small">{{ $methodCount }} {{ __('common.orders') }}</div>
                            </div>
                        </div>
                        <div class="fw-semibold text-end">{{ number_format($methodTotal, 2) }} DH</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="card content-card">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        <span>{{ __('admin.recent_orders') }}</span>
        <span class="text-secondary small">{{ __('admin.total_orders_count', ['count' => $orders->total()]) }}</span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>{{ __('common.details') }}</th>
                    <th>{{ __('common.user') }}</th>
                    <th>{{ __('common.email') }}</th>
                    <th>{{ __('common.items') }}</th>
                    <th>{{ __('common.total') }}</th>
                    <th>{{ __('admin.payment_method') }}</th>
                    <th>{{ __('admin.payment_status') }}</th>
                    <th>{{ __('common.date') }}</th>
                    <th>{{ __('common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>
                            <div class="fw-bold">#{{ $order->id }}</div>
                            <div class="text-secondary small">{{ ucfirst($order->status) }}</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="orders-customer-avatar">{{ \Illuminate\Support\Str::of($order->user?->name ?? $order->customer_name)->substr(0, 1)->upper() }}</span>
                                <div>
                                    <div class="fw-semibold">{{ $order->user?->name ?? $order->customer_name }}</div>
                                    <div class="text-secondary small">{{ $order->customer_phone ?: __('common.not_provided') }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $order->customer_email }}</td>
                        <td>{{ $order->items_count }}</td>
                        <td class="fw-semibold">{{ number_format($order->total_amount, 2) }} DH</td>
                        <td>
                            <span class="badge badge-soft-info">
                                <i class="{{ $methodIcons[$order->payment_method] ?? 'bi bi-wallet2' }} me-1"></i>
                                {{ __('store.payment_method_' . $order->payment_method) }}
                            </span>
                        </td>
                        <td><span class="badge {{ $statusBadgeClasses[$order->payment_status] ?? 'badge-soft-info' }}">{{ __('store.payment_status_' . $order->payment_status) }}</span></td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">{{ __('common.view') }}</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">{{ __('admin.no_orders') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($orders->hasPages())
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
@endif
@endsection
