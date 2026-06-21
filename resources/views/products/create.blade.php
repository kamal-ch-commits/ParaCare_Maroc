@extends('layouts.app')

@section('title', __('admin.create_product_title'))

@section('content')
<div class="section-header">
    <div>
        <h1 class="h3 page-title mb-1">{{ __('admin.create_product_title') }}</h1>
        <p class="section-subtitle">{{ __('admin.create_product_description') }}</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">{{ __('common.back') }}</a>
</div>
<div class="card content-card border-0">
    <div class="card-body p-4 p-lg-5">
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @include('products._form')
        </form>
    </div>
</div>
@endsection
