@extends('layouts.app')

@section('title', __('admin.add_category_title'))

@section('content')
<div class="section-header">
    <div>
        <h1 class="h3 page-title mb-1">{{ __('admin.add_category_title') }}</h1>
        <p class="section-subtitle">{{ __('admin.catalog_structure') }}</p>
    </div>
    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">{{ __('common.back') }}</a>
</div>
<div class="card content-card">
    <div class="card-body">
        <form method="POST" action="{{ route('categories.store') }}">
            @include('categories._form')
        </form>
    </div>
</div>
@endsection
