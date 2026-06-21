@extends('layouts.app')

@section('title', __('admin.products_index_title'))

@section('content')
<div class="section-header">
    <div>
        <h1 class="h3 page-title mb-1">{{ __('admin.products_index_title') }}</h1>
        <p class="section-subtitle">{{ __('admin.products_index_description') }}</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> {{ __('common.add') }}</a>
</div>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.products') }}</div><div class="h2 fw-bold mb-1">{{ $summary['total'] }}</div><div class="text-secondary small">{{ __('admin.catalog_managed') }}</div></div><span class="admin-metric-icon"><i class="bi bi-box-seam"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.low_stock') }}</div><div class="h2 fw-bold mb-1">{{ $summary['low_stock'] }}</div><div class="text-secondary small">{{ __('admin.low_stock_description') }}</div></div><span class="admin-metric-icon"><i class="bi bi-exclamation-triangle"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.expiring_soon') }}</div><div class="h2 fw-bold mb-1">{{ $summary['expiring_soon'] }}</div><div class="text-secondary small">{{ __('admin.expiring_soon_description') }}</div></div><span class="admin-metric-icon"><i class="bi bi-calendar2-week"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.total') }}</div><div class="h2 fw-bold mb-1">{{ number_format($summary['stock_value'], 2) }} DH</div><div class="text-secondary small">{{ __('common.stock') }}</div></div><span class="admin-metric-icon"><i class="bi bi-cash-stack"></i></span></div></div></div>
</div>
<form class="row g-2 mb-3 admin-filter-bar" method="GET" action="{{ route('products.index') }}">
    <div class="col-md-5">
        <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="{{ __('admin.search_name_or_code') }}">
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i> {{ __('common.search') }}</button>
    </div>
</form>
<div class="card content-card">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        <span>{{ __('admin.products_index_title') }}</span>
        <span class="text-secondary small">{{ __('admin.total_records_count', ['count' => $products->total()]) }}</span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>{{ __('common.image') }}</th><th>{{ __('common.code') }}</th><th>{{ __('common.product') }}</th><th>{{ __('common.category') }}</th><th>{{ __('common.supplier') }}</th><th>{{ __('common.sale_price') }}</th><th>{{ __('common.stock') }}</th><th>{{ __('common.photos') }}</th><th>{{ __('common.expiration') }}</th><th class="text-end">{{ __('common.actions') }}</th></tr></thead>
            <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>
                        <img src="{{ $product->image_url }}" alt="{{ $product->translated_name }}" class="rounded-3 border" style="width: 60px; height: 60px; object-fit: cover;">
                    </td>
                    <td><span class="badge badge-soft-info">{{ $product->code }}</span></td>
                    <td class="fw-semibold">{{ $product->translated_name }}</td>
                    <td>{{ $product->category?->translated_name }}</td>
                    <td>{{ $product->supplier?->name ?? '-' }}</td>
                    <td>{{ number_format($product->sale_price, 2) }} DH</td>
                    <td>
                        @if ($product->stock_quantity <= $product->minimum_threshold)
                            <span class="badge badge-soft-danger">{{ $product->stock_quantity }}</span>
                        @else
                            <span class="badge badge-soft-success">{{ $product->stock_quantity }}</span>
                        @endif
                    </td>
                    <td><span class="badge badge-soft-info">{{ $product->gallery_images->count() }}</span></td>
                    <td>{{ $product->expiration_date?->format('d/m/Y') ?? '-' }}</td>
                    <td class="text-end">
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">{{ __('common.edit') }}</a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline" onsubmit="return confirm('{{ __('common.delete') }} ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">{{ __('common.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="10" class="text-center text-muted py-4">{{ __('admin.no_products_found') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $products->links() }}</div>
@endsection
