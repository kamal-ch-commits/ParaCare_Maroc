@extends('layouts.app')

@section('title', __('admin.edit_supplier_title'))

@section('content')
<div class="section-header">
    <div>
        <h1 class="h3 page-title mb-1">{{ __('admin.edit_supplier_title') }}</h1>
        <p class="section-subtitle">{{ __('admin.active_suppliers') }}</p>
    </div>
    <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">{{ __('common.back') }}</a>
</div>
<div class="card content-card">
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
            @method('PUT')
            @include('suppliers._form')
        </form>
    </div>
</div>
@endsection
