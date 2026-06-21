@extends('layouts.app')

@section('title', __('admin.low_stock_title'))

@section('content')
<div class="section-header">
    <div>
        <h1 class="h3 page-title mb-1">{{ __('admin.low_stock_title') }}</h1>
        <p class="text-muted mb-0">{{ __('admin.low_stock_description') }}</p>
    </div>
    <a href="{{ route('stock-entries.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> {{ __('admin.restock') }}</a>
</div>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.low_stock') }}</div><div class="h2 fw-bold mb-1">{{ $summary['low_stock'] }}</div><div class="text-secondary small">{{ __('admin.low_stock_description') }}</div></div><span class="admin-metric-icon"><i class="bi bi-exclamation-triangle"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.out_of_stock') }}</div><div class="h2 fw-bold mb-1">{{ $summary['out_of_stock'] }}</div><div class="text-secondary small">{{ __('admin.out_of_stock_description') }}</div></div><span class="admin-metric-icon"><i class="bi bi-x-circle"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.soon') }}</div><div class="h2 fw-bold mb-1">{{ $summary['available_low_stock'] }}</div><div class="text-secondary small">{{ __('common.stock') }}</div></div><span class="admin-metric-icon"><i class="bi bi-speedometer"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.products') }}</div><div class="h2 fw-bold mb-1">{{ $summary['total_products'] }}</div><div class="text-secondary small">{{ __('admin.catalog_managed') }}</div></div><span class="admin-metric-icon"><i class="bi bi-box-seam"></i></span></div></div></div>
</div>
<div class="card content-card">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        <span>{{ __('admin.low_stock_alerts') }}</span>
        <span class="text-secondary small">{{ __('admin.total_records_count', ['count' => $products->total()]) }}</span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>{{ __('common.code') }}</th><th>{{ __('common.product') }}</th><th>{{ __('common.category') }}</th><th>{{ __('common.supplier') }}</th><th>{{ __('common.stock') }}</th><th>{{ __('admin.minimum_threshold') }}</th><th>{{ __('admin.state') }}</th></tr></thead>
            <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->code }}</td>
                    <td class="fw-semibold">{{ $product->translated_name }}</td>
                    <td>{{ $product->category?->translated_name }}</td>
                    <td>{{ $product->supplier?->name ?? '-' }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>{{ $product->minimum_threshold }}</td>
                    <td>
                        @if ($product->stock_quantity === 0)
                            <span class="badge badge-soft-danger">{{ __('admin.out_of_stock') }}</span>
                        @else
                            <span class="badge badge-soft-warning">{{ __('common.low_stock') }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">{{ __('admin.no_low_stock_alerts') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $products->links() }}</div>
@endsection
