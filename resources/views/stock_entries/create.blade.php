@extends('layouts.app')

@section('title', __('admin.new_stock_entry_title'))

@section('content')
<div class="section-header">
    <div>
        <h1 class="h3 page-title mb-1">{{ __('admin.new_stock_entry_title') }}</h1>
        <p class="section-subtitle">{{ __('admin.stock_entries_count_description') }}</p>
    </div>
    <a href="{{ route('stock-entries.index') }}" class="btn btn-outline-secondary">{{ __('common.back') }}</a>
</div>
<div class="card content-card">
    <div class="card-body">
        <form method="POST" action="{{ route('stock-entries.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="product_id">{{ __('common.product') }}</label>
                    <select id="product_id" name="product_id" class="form-select" required>
                        <option value="">{{ __('admin.choose') }}</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->code }} - {{ $product->translated_name }} ({{ __('common.stock') }}: {{ $product->stock_quantity }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="supplier_id">{{ __('common.supplier') }}</label>
                    <select id="supplier_id" name="supplier_id" class="form-select">
                        <option value="">{{ __('admin.no_supplier') }}</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label" for="quantity">{{ __('common.quantity') }}</label>
                    <input type="number" min="1" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" class="form-control" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label" for="purchase_price">{{ __('common.purchase_price') }}</label>
                    <input type="number" step="0.01" min="0" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', 0) }}" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label" for="entry_date">{{ __('admin.entry_date') }}</label>
                    <input type="date" id="entry_date" name="entry_date" value="{{ old('entry_date', now()->format('Y-m-d')) }}" class="form-control" required>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit">{{ __('common.save') }}</button>
                <a href="{{ route('stock-entries.index') }}" class="btn btn-outline-secondary">{{ __('common.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
