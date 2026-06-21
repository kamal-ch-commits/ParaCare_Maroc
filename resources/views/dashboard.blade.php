@extends('layouts.app')

@section('title', __('common.dashboard'))

@section('content')
<div class="section-header">
    <div>
        <h1 class="h2 page-title mb-1">{{ __('admin.dashboard_title') }}</h1>
        <p class="text-muted mb-0">{{ __('admin.dashboard_description') }}</p>
    </div>
    <div class="d-flex action-bar">
        <a href="{{ route('stock-entries.create') }}" class="btn btn-outline-primary"><i class="bi bi-plus-square"></i> {{ __('admin.stock_entry') }}</a>
        <a href="{{ route('sales.create') }}" class="btn btn-primary"><i class="bi bi-receipt"></i> {{ __('admin.new_sale') }}</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="small text-uppercase text-muted mb-2">{{ __('common.products') }}</div>
                <h2 class="mb-1">{{ $totalProducts }}</h2>
                <div class="text-secondary small">{{ __('admin.catalog_managed') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="small text-uppercase text-muted mb-2">{{ __('common.categories') }}</div>
                <h2 class="mb-1">{{ $totalCategories }}</h2>
                <div class="text-secondary small">{{ __('admin.catalog_structure') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="small text-uppercase text-muted mb-2">{{ __('common.suppliers') }}</div>
                <h2 class="mb-1">{{ $totalSuppliers }}</h2>
                <div class="text-secondary small">{{ __('admin.active_suppliers') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="small text-uppercase text-muted mb-2">{{ __('admin.out_of_stock') }}</div>
                <h2 class="mb-1">{{ $outOfStockCount }}</h2>
                <div class="text-secondary small">{{ __('admin.out_of_stock_description') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="small text-uppercase text-muted mb-2">{{ __('admin.expiring_soon') }}</div>
                <h2 class="mb-1">{{ $closeToExpirationCount }}</h2>
                <div class="text-secondary small">{{ __('admin.expiring_soon_description') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="small text-uppercase text-muted mb-2">{{ __('admin.total_sales') }}</div>
                <h2 class="mb-1">{{ number_format($totalSales, 2) }} DH</h2>
                <div class="text-secondary small">{{ __('admin.total_sales_description') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="small text-uppercase text-muted mb-2">{{ __('admin.sales_count') }}</div>
                <h2 class="mb-1">{{ $salesCount }}</h2>
                <div class="text-secondary small">{{ __('admin.sales_count_description') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="small text-uppercase text-muted mb-2">{{ __('admin.stock_entries_count') }}</div>
                <h2 class="mb-1">{{ $stockEntriesCount }}</h2>
                <div class="text-secondary small">{{ __('admin.stock_entries_count_description') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="small text-uppercase text-muted mb-2">{{ __('common.orders') }}</div>
                <h2 class="mb-1">{{ $ordersCount }}</h2>
                <div class="text-secondary small">{{ __('admin.orders_count_description') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="small text-uppercase text-muted mb-2">{{ __('admin.pending_payments') }}</div>
                <h2 class="mb-1">{{ $pendingPaymentOrdersCount }}</h2>
                <div class="text-secondary small">{{ __('admin.pending_payments_description') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card content-card h-100">
            <div class="card-header bg-white fw-semibold">{{ __('admin.low_stock_alerts') }}</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>{{ __('common.product') }}</th><th>{{ __('common.category') }}</th><th>{{ __('common.stock') }}</th><th>{{ __('admin.minimum_threshold') }}</th></tr></thead>
                    <tbody>
                    @forelse ($lowStockProducts as $product)
                        <tr>
                            <td>{{ $product->translated_name }}</td>
                            <td>{{ $product->category?->translated_name }}</td>
                            <td><span class="badge badge-soft-danger">{{ $product->stock_quantity }}</span></td>
                            <td>{{ $product->minimum_threshold }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">{{ __('admin.no_low_stock_alerts') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card content-card h-100">
            <div class="card-header bg-white fw-semibold">{{ __('admin.near_expiration_products') }}</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>{{ __('common.product') }}</th><th>{{ __('common.category') }}</th><th>{{ __('admin.expiration_date') }}</th><th>{{ __('admin.state') }}</th></tr></thead>
                    <tbody>
                    @forelse ($closeToExpirationProducts as $product)
                        <tr>
                            <td>{{ $product->translated_name }}</td>
                            <td>{{ $product->category?->translated_name }}</td>
                            <td>{{ $product->expiration_date?->format('d/m/Y') }}</td>
                            <td><span class="badge badge-soft-warning">{{ __('admin.soon') }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">{{ __('admin.no_expiration_alerts') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-6">
        <div class="card content-card h-100">
            <div class="card-header bg-white fw-semibold">{{ __('admin.expired_products') }}</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>{{ __('common.product') }}</th><th>{{ __('common.category') }}</th><th>{{ __('admin.expiration_date') }}</th></tr></thead>
                    <tbody>
                    @forelse ($expiredProducts as $product)
                        <tr>
                            <td>{{ $product->translated_name }}</td>
                            <td>{{ $product->category?->translated_name }}</td>
                            <td><span class="badge badge-soft-danger">{{ $product->expiration_date?->format('d/m/Y') }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">{{ __('admin.no_expired_products') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card content-card h-100">
            <div class="card-header bg-white fw-semibold">{{ __('admin.recent_sales') }}</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>{{ __('common.date') }}</th><th>{{ __('common.user') }}</th><th>{{ __('common.total') }}</th></tr></thead>
                    <tbody>
                    @forelse ($recentSales as $sale)
                        <tr>
                            <td>{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $sale->user?->name }}</td>
                            <td>{{ number_format($sale->total_amount, 2) }} DH</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">{{ __('admin.no_sales_recorded') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-12">
        <div class="card content-card h-100">
            <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                <span>{{ __('admin.recent_orders') }}</span>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">{{ __('common.view') }}</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>{{ __('common.details') }}</th><th>{{ __('common.user') }}</th><th>{{ __('common.total') }}</th><th>{{ __('admin.payment_method') }}</th><th>{{ __('admin.payment_status') }}</th><th>{{ __('common.date') }}</th></tr></thead>
                    <tbody>
                    @forelse ($recentOrders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->user?->name ?? $order->customer_name }}</td>
                            <td>{{ number_format($order->total_amount, 2) }} DH</td>
                            <td>{{ __('store.payment_method_' . $order->payment_method) }}</td>
                            <td>{{ __('store.payment_status_' . $order->payment_status) }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">{{ __('admin.no_orders') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
