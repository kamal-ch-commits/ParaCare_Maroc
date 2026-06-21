@extends('layouts.app')

@section('title', __('admin.new_sale_title'))

@section('content')
<div class="section-header">
    <div>
        <h1 class="h3 page-title mb-1">{{ __('admin.new_sale_title') }}</h1>
        <p class="section-subtitle">{{ __('admin.sales_count_description') }}</p>
    </div>
    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">{{ __('common.back') }}</a>
</div>
<div class="card content-card">
    <div class="card-body">
        <form method="POST" action="{{ route('sales.store') }}" id="saleForm">
            @csrf
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label" for="sale_date">{{ __('admin.sale_date') }}</label>
                    <input type="datetime-local" id="sale_date" name="sale_date" value="{{ old('sale_date', now()->format('Y-m-d\TH:i')) }}" class="form-control" required>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle" id="itemsTable">
                    <thead><tr><th style="width:45%">{{ __('common.product') }}</th><th>{{ __('common.quantity') }}</th><th>{{ __('common.price') }}</th><th>{{ __('common.subtotal') }}</th><th class="text-end">{{ __('common.actions') }}</th></tr></thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="items[0][product_id]" class="form-select product-select" required>
                                    <option value="">{{ __('admin.choose') }}</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->sale_price }}" data-stock="{{ $product->stock_quantity }}">{{ $product->code }} - {{ $product->translated_name }} | {{ number_format($product->sale_price, 2) }} DH | {{ __('common.stock') }} {{ $product->stock_quantity }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" min="1" name="items[0][quantity]" value="1" class="form-control quantity-input" required></td>
                            <td class="unit-price">0.00 DH</td>
                            <td class="subtotal fw-semibold">0.00 DH</td>
                            <td class="text-end"><button type="button" class="btn btn-outline-danger btn-sm remove-row">{{ __('admin.remove_row') }}</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <button type="button" id="addRow" class="btn btn-outline-primary"><i class="bi bi-plus-lg"></i> {{ __('admin.add_row') }}</button>
                <div class="h4 mb-0">{{ __('common.total') }}: <span id="saleTotal">0.00 DH</span></div>
            </div>

            <hr>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit">{{ __('admin.save_sale') }}</button>
                <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">{{ __('common.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const tableBody = document.querySelector('#itemsTable tbody');
const productsHtml = `{!! $products->map(fn ($product) => '<option value="'.$product->id.'" data-price="'.$product->sale_price.'" data-stock="'.$product->stock_quantity.'">'.e($product->code.' - '.$product->translated_name.' | '.number_format($product->sale_price, 2).' DH | '.__('common.stock').' '.$product->stock_quantity).'</option>')->prepend('<option value="">'.__('admin.choose').'</option>')->implode('') !!}`;
let rowIndex = 1;

function money(value) {
    return Number(value || 0).toFixed(2) + ' DH';
}

function recalculate() {
    let total = 0;
    tableBody.querySelectorAll('tr').forEach((row) => {
        const select = row.querySelector('.product-select');
        const quantity = Number(row.querySelector('.quantity-input').value || 0);
        const option = select.options[select.selectedIndex];
        const price = Number(option?.dataset.price || 0);
        const stock = Number(option?.dataset.stock || 0);
        if (quantity > stock && stock > 0) {
            row.querySelector('.quantity-input').classList.add('is-invalid');
        } else {
            row.querySelector('.quantity-input').classList.remove('is-invalid');
        }
        const subtotal = price * quantity;
        total += subtotal;
        row.querySelector('.unit-price').textContent = money(price);
        row.querySelector('.subtotal').textContent = money(subtotal);
    });
    document.getElementById('saleTotal').textContent = money(total);
}

document.getElementById('addRow').addEventListener('click', () => {
    const row = document.createElement('tr');
    row.innerHTML = `
        <td><select name="items[${rowIndex}][product_id]" class="form-select product-select" required>${productsHtml}</select></td>
        <td><input type="number" min="1" name="items[${rowIndex}][quantity]" value="1" class="form-control quantity-input" required></td>
        <td class="unit-price">0.00 DH</td>
        <td class="subtotal fw-semibold">0.00 DH</td>
        <td class="text-end"><button type="button" class="btn btn-outline-danger btn-sm remove-row">{{ __('admin.remove_row') }}</button></td>
    `;
    rowIndex++;
    tableBody.appendChild(row);
});

tableBody.addEventListener('change', recalculate);
tableBody.addEventListener('input', recalculate);
tableBody.addEventListener('click', (event) => {
    if (event.target.classList.contains('remove-row') && tableBody.rows.length > 1) {
        event.target.closest('tr').remove();
        recalculate();
    }
});
recalculate();
</script>
@endpush
