@extends('layouts.app')

@section('title', __('admin.expiration_title'))

@section('content')
<div class="section-header">
    <div>
        <h1 class="h3 page-title mb-1">{{ __('admin.expiration_title') }}</h1>
        <p class="text-muted mb-0">{{ __('admin.expiration_description') }}</p>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.products') }}</div><div class="h2 fw-bold mb-1">{{ $summary['tracked'] }}</div><div class="text-secondary small">{{ __('admin.expiration_date') }}</div></div><span class="admin-metric-icon"><i class="bi bi-calendar-check"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.expired') }}</div><div class="h2 fw-bold mb-1">{{ $summary['expired'] }}</div><div class="text-secondary small">{{ __('admin.expired_products') }}</div></div><span class="admin-metric-icon"><i class="bi bi-x-circle"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.soon') }}</div><div class="h2 fw-bold mb-1">{{ $summary['soon'] }}</div><div class="text-secondary small">{{ __('admin.expiring_soon_description') }}</div></div><span class="admin-metric-icon"><i class="bi bi-hourglass-split"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.valid') }}</div><div class="h2 fw-bold mb-1">{{ $summary['valid'] }}</div><div class="text-secondary small">{{ __('admin.no_expiration_alerts') }}</div></div><span class="admin-metric-icon"><i class="bi bi-check2-circle"></i></span></div></div></div>
</div>
<form class="row g-2 mb-3 admin-filter-bar" method="GET" action="{{ route('alerts.expiration') }}">
    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">{{ __('admin.all_statuses') }}</option>
            <option value="expired" @selected($status === 'expired')>{{ __('admin.expired_filter') }}</option>
            <option value="soon" @selected($status === 'soon')>{{ __('admin.soon_filter') }}</option>
        </select>
    </div>
    <div class="col-md-2"><input type="date" name="from" value="{{ $from }}" class="form-control"></div>
    <div class="col-md-2"><input type="date" name="to" value="{{ $to }}" class="form-control"></div>
    <div class="col-auto"><button class="btn btn-outline-primary" type="submit"><i class="bi bi-funnel"></i> {{ __('common.filter') }}</button></div>
</form>
<div class="card content-card">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        <span>{{ __('admin.expiration_title') }}</span>
        <span class="text-secondary small">{{ __('admin.total_records_count', ['count' => $products->total()]) }}</span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>{{ __('common.code') }}</th><th>{{ __('common.product') }}</th><th>{{ __('common.category') }}</th><th>{{ __('common.supplier') }}</th><th>{{ __('common.stock') }}</th><th>{{ __('admin.expiration_date') }}</th><th>{{ __('admin.state') }}</th></tr></thead>
            <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->code }}</td>
                    <td class="fw-semibold">{{ $product->translated_name }}</td>
                    <td>{{ $product->category?->translated_name }}</td>
                    <td>{{ $product->supplier?->name ?? '-' }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>{{ $product->expiration_date?->format('d/m/Y') }}</td>
                    <td>
                        @if ($product->expiration_date && $product->expiration_date->isPast())
                            <span class="badge badge-soft-danger">{{ __('admin.expired') }}</span>
                        @elseif ($product->expiration_date && $product->expiration_date->lte(now()->addDays(30)))
                            <span class="badge badge-soft-warning">{{ __('admin.soon') }}</span>
                        @else
                            <span class="badge badge-soft-success">{{ __('admin.valid') }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">{{ __('admin.no_products_found') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $products->links() }}</div>
@endsection
