@extends('layouts.app')

@section('title', __('admin.history_sales_title'))

@section('content')
<div class="section-header">
    <div>
        <h1 class="h3 page-title mb-1">{{ __('admin.history_sales_title') }}</h1>
        <p class="section-subtitle">{{ __('admin.total_sales_description') }}</p>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.total_sales') }}</div><div class="h2 fw-bold mb-1">{{ number_format($summary['total_sales'], 2) }} DH</div><div class="text-secondary small">{{ __('admin.total_sales_description') }}</div></div><span class="admin-metric-icon"><i class="bi bi-cash-stack"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.sales_count') }}</div><div class="h2 fw-bold mb-1">{{ $summary['sales_count'] }}</div><div class="text-secondary small">{{ __('admin.sales_count_description') }}</div></div><span class="admin-metric-icon"><i class="bi bi-receipt"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.last_30_days') }}</div><div class="h2 fw-bold mb-1">{{ number_format($summary['month_sales'], 2) }} DH</div><div class="text-secondary small">{{ __('admin.total_sales_description') }}</div></div><span class="admin-metric-icon"><i class="bi bi-graph-up"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.items') }}</div><div class="h2 fw-bold mb-1">{{ $summary['items_sold'] }}</div><div class="text-secondary small">{{ __('common.products') }}</div></div><span class="admin-metric-icon"><i class="bi bi-basket2"></i></span></div></div></div>
</div>
<form class="row g-2 mb-3 admin-filter-bar" method="GET" action="{{ route('history.sales') }}">
    <div class="col-md-5"><input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="{{ __('admin.search_sold_product') }}"></div>
    <div class="col-auto"><button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i> {{ __('common.search') }}</button></div>
</form>
<div class="card content-card">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        <span>{{ __('admin.history_sales_title') }}</span>
        <span class="text-secondary small">{{ __('admin.total_records_count', ['count' => $sales->total()]) }}</span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>{{ __('common.date') }}</th><th>{{ __('common.user') }}</th><th>{{ __('common.products') }}</th><th>{{ __('common.items') }}</th><th>{{ __('common.total') }}</th><th class="text-end">{{ __('common.details') }}</th></tr></thead>
            <tbody>
            @forelse ($sales as $sale)
                <tr>
                    <td>{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                    <td>{{ $sale->user?->name }}</td>
                    <td>{{ $sale->details->map(fn ($detail) => $detail->product?->translated_name)->filter()->join(', ') }}</td>
                    <td>{{ $sale->details->sum('quantity') }}</td>
                    <td>{{ number_format($sale->total_amount, 2) }} DH</td>
                    <td class="text-end"><a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-primary">{{ __('common.view') }}</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">{{ __('admin.no_history') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $sales->links() }}</div>
@endsection
