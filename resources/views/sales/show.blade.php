@extends('layouts.app')

@section('title', __('admin.sale_details_title'))

@section('content')
<div class="section-header">
    <div>
        <h1 class="h3 page-title mb-1">{{ __('common.details') }} #{{ $sale->id }}</h1>
        <p class="text-muted mb-0">{{ $sale->sale_date->format('d/m/Y H:i') }} {{ __('common.user') }} {{ $sale->user?->name }}</p>
    </div>
    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">{{ __('common.back') }}</a>
</div>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.total') }}</div><div class="h2 fw-bold mb-1">{{ number_format($sale->total_amount, 2) }} DH</div><div class="text-secondary small">{{ __('admin.sale_details_title') }}</div></div><span class="admin-metric-icon"><i class="bi bi-cash-stack"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.items') }}</div><div class="h2 fw-bold mb-1">{{ $sale->details->sum('quantity') }}</div><div class="text-secondary small">{{ __('common.products') }}</div></div><span class="admin-metric-icon"><i class="bi bi-basket2"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.products') }}</div><div class="h2 fw-bold mb-1">{{ $sale->details->count() }}</div><div class="text-secondary small">{{ __('admin.recorded_at') }}</div></div><span class="admin-metric-icon"><i class="bi bi-box-seam"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.user') }}</div><div class="h5 fw-bold mb-1">{{ $sale->user?->name }}</div><div class="text-secondary small">{{ $sale->sale_date->format('d/m/Y H:i') }}</div></div><span class="admin-metric-icon"><i class="bi bi-person-circle"></i></span></div></div></div>
</div>
<div class="card content-card">
    <div class="card-header bg-white fw-semibold">{{ __('admin.sale_details_title') }}</div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>{{ __('common.code') }}</th><th>{{ __('common.product') }}</th><th>{{ __('common.quantity') }}</th><th>{{ __('common.unit_price') }}</th><th>{{ __('common.subtotal') }}</th></tr></thead>
            <tbody>
            @foreach ($sale->details as $detail)
                <tr>
                    <td>{{ $detail->product?->code }}</td>
                    <td>{{ $detail->product?->translated_name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ number_format($detail->unit_price, 2) }} DH</td>
                    <td>{{ number_format($detail->subtotal, 2) }} DH</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot><tr><th colspan="4" class="text-end">{{ __('common.total') }}</th><th>{{ number_format($sale->total_amount, 2) }} DH</th></tr></tfoot>
        </table>
    </div>
</div>
@endsection
