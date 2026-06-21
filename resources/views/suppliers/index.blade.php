@extends('layouts.app')

@section('title', __('admin.suppliers_title'))

@section('content')
<div class="section-header">
    <div>
        <h1 class="h3 page-title mb-1">{{ __('admin.suppliers_title') }}</h1>
        <p class="section-subtitle">{{ __('admin.active_suppliers') }}</p>
    </div>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> {{ __('common.add') }}</a>
</div>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.suppliers') }}</div><div class="h2 fw-bold mb-1">{{ $summary['total'] }}</div><div class="text-secondary small">{{ __('admin.active_suppliers') }}</div></div><span class="admin-metric-icon"><i class="bi bi-truck"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.products') }}</div><div class="h2 fw-bold mb-1">{{ $summary['linked_products'] }}</div><div class="text-secondary small">{{ __('admin.catalog_managed') }}</div></div><span class="admin-metric-icon"><i class="bi bi-box-seam"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.valid') }}</div><div class="h2 fw-bold mb-1">{{ $summary['with_products'] }}</div><div class="text-secondary small">{{ __('common.products') }}</div></div><span class="admin-metric-icon"><i class="bi bi-check2-circle"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.stock_entries') }}</div><div class="h2 fw-bold mb-1">{{ $summary['with_stock_entries'] }}</div><div class="text-secondary small">{{ __('admin.stock_entries_count_description') }}</div></div><span class="admin-metric-icon"><i class="bi bi-plus-square"></i></span></div></div></div>
</div>
<div class="card content-card">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        <span>{{ __('admin.suppliers_title') }}</span>
        <span class="text-secondary small">{{ __('admin.total_records_count', ['count' => $suppliers->total()]) }}</span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>{{ __('common.name') }}</th><th>{{ __('common.phone') }}</th><th>{{ __('common.email') }}</th><th>{{ __('common.address') }}</th><th>{{ __('common.products') }}</th><th class="text-end">{{ __('common.actions') }}</th></tr></thead>
            <tbody>
            @forelse ($suppliers as $supplier)
                <tr>
                    <td class="fw-semibold">{{ $supplier->name }}</td>
                    <td>{{ $supplier->phone }}</td>
                    <td>{{ $supplier->email }}</td>
                    <td>{{ $supplier->address }}</td>
                    <td><span class="badge badge-soft-info">{{ $supplier->products_count }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-primary">{{ __('common.edit') }}</a>
                        <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" class="d-inline" onsubmit="return confirm('{{ __('common.delete') }} ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">{{ __('common.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">{{ __('admin.no_suppliers') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $suppliers->links() }}</div>
@endsection
