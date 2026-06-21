@extends('layouts.app')

@section('title', __('admin.categories_title'))

@section('content')
<div class="section-header">
    <div>
        <h1 class="h3 page-title mb-1">{{ __('admin.categories_title') }}</h1>
        <p class="section-subtitle">{{ __('admin.catalog_structure') }}</p>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> {{ __('common.add') }}</a>
</div>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.categories') }}</div><div class="h2 fw-bold mb-1">{{ $summary['total'] }}</div><div class="text-secondary small">{{ __('admin.catalog_structure') }}</div></div><span class="admin-metric-icon"><i class="bi bi-tags"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.products') }}</div><div class="h2 fw-bold mb-1">{{ $summary['linked_products'] }}</div><div class="text-secondary small">{{ __('admin.catalog_managed') }}</div></div><span class="admin-metric-icon"><i class="bi bi-box-seam"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.active_categories') }}</div><div class="h2 fw-bold mb-1">{{ $summary['with_products'] }}</div><div class="text-secondary small">{{ __('common.products') }}</div></div><span class="admin-metric-icon"><i class="bi bi-check2-circle"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.empty_categories') }}</div><div class="h2 fw-bold mb-1">{{ $summary['empty'] }}</div><div class="text-secondary small">{{ __('admin.catalog_structure') }}</div></div><span class="admin-metric-icon"><i class="bi bi-folder-x"></i></span></div></div></div>
</div>
<div class="card content-card">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        <span>{{ __('admin.categories_title') }}</span>
        <span class="text-secondary small">{{ __('admin.total_records_count', ['count' => $categories->total()]) }}</span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>{{ __('common.name') }}</th><th>{{ __('common.description') }}</th><th>{{ __('common.products') }}</th><th class="text-end">{{ __('common.actions') }}</th></tr></thead>
            <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td class="fw-semibold">{{ $category->translated_name }}</td>
                    <td>{{ $category->translated_description }}</td>
                    <td><span class="badge badge-soft-info">{{ $category->products_count }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">{{ __('common.edit') }}</a>
                        <form method="POST" action="{{ route('categories.destroy', $category) }}" class="d-inline" onsubmit="return confirm('{{ __('common.delete') }} ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">{{ __('common.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">{{ __('admin.no_categories') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $categories->links() }}</div>
@endsection
