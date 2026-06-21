@extends('layouts.app')

@section('title', __('admin.history_stock_entries_title'))

@section('content')
<div class="section-header">
    <div>
        <h1 class="h3 page-title mb-1">{{ __('admin.history_stock_entries_title') }}</h1>
        <p class="section-subtitle">{{ __('admin.stock_entries_count_description') }}</p>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.stock_entries_count') }}</div><div class="h2 fw-bold mb-1">{{ $summary['entries_count'] }}</div><div class="text-secondary small">{{ __('admin.stock_entries_count_description') }}</div></div><span class="admin-metric-icon"><i class="bi bi-plus-square"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.quantity') }}</div><div class="h2 fw-bold mb-1">{{ $summary['quantity_received'] }}</div><div class="text-secondary small">{{ __('common.stock_entries') }}</div></div><span class="admin-metric-icon"><i class="bi bi-box-arrow-in-down"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.last_30_days') }}</div><div class="h2 fw-bold mb-1">{{ $summary['month_quantity'] }}</div><div class="text-secondary small">{{ __('admin.received_quantity') }}</div></div><span class="admin-metric-icon"><i class="bi bi-calendar2-week"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.purchase_price') }}</div><div class="h2 fw-bold mb-1">{{ number_format($summary['average_purchase_price'], 2) }} DH</div><div class="text-secondary small">{{ __('admin.average_order_value') }}</div></div><span class="admin-metric-icon"><i class="bi bi-calculator"></i></span></div></div></div>
</div>
<form class="row g-2 mb-3 admin-filter-bar" method="GET" action="{{ route('history.stock-entries') }}">
    <div class="col-md-5"><input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="{{ __('admin.search_product_or_supplier') }}"></div>
    <div class="col-auto"><button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i> {{ __('common.search') }}</button></div>
</form>
<div class="card content-card">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        <span>{{ __('admin.history_stock_entries_title') }}</span>
        <span class="text-secondary small">{{ __('admin.total_records_count', ['count' => $stockEntries->total()]) }}</span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>{{ __('common.date') }}</th><th>{{ __('common.product') }}</th><th>{{ __('common.supplier') }}</th><th>{{ __('common.quantity') }}</th><th>{{ __('common.purchase_price') }}</th><th>{{ __('admin.recorded_at') }}</th></tr></thead>
            <tbody>
            @forelse ($stockEntries as $entry)
                <tr>
                    <td>{{ $entry->entry_date->format('d/m/Y') }}</td>
                    <td>{{ $entry->product?->translated_name }}</td>
                    <td>{{ $entry->supplier?->name ?? '-' }}</td>
                    <td>{{ $entry->quantity }}</td>
                    <td>{{ number_format($entry->purchase_price, 2) }} DH</td>
                    <td>{{ $entry->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">{{ __('admin.no_history') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $stockEntries->links() }}</div>
@endsection
