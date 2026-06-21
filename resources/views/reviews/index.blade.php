@extends('layouts.app')

@section('title', __('admin.reviews_title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 page-title mb-1">{{ __('admin.reviews_title') }}</h1>
        <p class="section-subtitle">{{ __('admin.reviews_description') }}</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.reviews_title') }}</div><div class="h2 fw-bold mb-1">{{ $summary['total'] }}</div><div class="text-secondary small">{{ __('admin.reviews_description') }}</div></div><span class="admin-metric-icon"><i class="bi bi-chat-left-text"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.review_pending') }}</div><div class="h2 fw-bold mb-1">{{ $summary['pending'] }}</div><div class="text-secondary small">{{ __('common.status') }}</div></div><span class="admin-metric-icon"><i class="bi bi-hourglass-split"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('admin.review_approved') }}</div><div class="h2 fw-bold mb-1">{{ $summary['approved'] }}</div><div class="text-secondary small">{{ __('admin.approve_review') }}</div></div><span class="admin-metric-icon"><i class="bi bi-check2-circle"></i></span></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card admin-metric-card"><div class="d-flex justify-content-between position-relative"><div><div class="text-secondary small fw-semibold mb-2">{{ __('common.rating') }}</div><div class="h2 fw-bold mb-1">{{ number_format($summary['average_rating'], 1) }}/5</div><div class="text-secondary small">{{ __('admin.all_ratings') }}</div></div><span class="admin-metric-icon"><i class="bi bi-star-half"></i></span></div></div></div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card content-card h-100">
            <div class="card-header bg-white fw-semibold">{{ __('common.status') }}</div>
            <div class="card-body d-flex flex-column gap-3">
                @foreach (['pending' => __('admin.review_pending'), 'approved' => __('admin.review_approved'), 'rejected' => __('admin.review_rejected')] as $reviewStatus => $label)
                    @php
                        $count = (int) ($statusBreakdown->get($reviewStatus)?->reviews_count ?? 0);
                        $percent = $summary['total'] > 0 ? round(($count / $summary['total']) * 100) : 0;
                    @endphp
                    <div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">{{ $label }}</span>
                            <span class="text-secondary small">{{ $count }}</span>
                        </div>
                        <div class="admin-progress"><span style="width: {{ $percent }}%"></span></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card content-card h-100">
            <div class="card-header bg-white fw-semibold">{{ __('admin.quick_checklist') }}</div>
            <div class="card-body d-flex flex-column gap-3">
                <div class="admin-mini-panel d-flex align-items-center gap-3"><span class="admin-metric-icon"><i class="bi bi-eye"></i></span><div><div class="fw-semibold">{{ __('admin.review_pending') }}</div><div class="text-secondary small">{{ $summary['pending'] }} {{ __('common.status') }}</div></div></div>
                <div class="admin-mini-panel d-flex align-items-center gap-3"><span class="admin-metric-icon"><i class="bi bi-check2"></i></span><div><div class="fw-semibold">{{ __('admin.review_approved') }}</div><div class="text-secondary small">{{ $summary['approved'] }} {{ __('admin.approve_review') }}</div></div></div>
            </div>
        </div>
    </div>
</div>

<form class="row g-2 mb-3 admin-filter-bar" method="GET" action="{{ route('reviews.index') }}">
    <div class="col-md-4">
        <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="{{ __('admin.search_reviews') }}">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">{{ __('admin.all_statuses') }}</option>
            <option value="pending" @selected($status === 'pending')>{{ __('admin.review_pending') }}</option>
            <option value="approved" @selected($status === 'approved')>{{ __('admin.review_approved') }}</option>
            <option value="rejected" @selected($status === 'rejected')>{{ __('admin.review_rejected') }}</option>
        </select>
    </div>
    <div class="col-md-3">
        <select name="rating" class="form-select">
            <option value="">{{ __('admin.all_ratings') }}</option>
            @foreach (range(5, 1) as $ratingValue)
                <option value="{{ $ratingValue }}" @selected($rating === $ratingValue)>{{ $ratingValue }} / 5</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 d-grid">
        <button class="btn btn-outline-primary" type="submit">{{ __('common.filter') }}</button>
    </div>
</form>

<div class="card content-card">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        <span>{{ __('admin.reviews_title') }}</span>
        <span class="text-secondary small">{{ __('admin.total_records_count', ['count' => $reviews->total()]) }}</span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>{{ __('common.product') }}</th>
                    <th>{{ __('common.user') }}</th>
                    <th>{{ __('common.rating') }}</th>
                    <th>{{ __('common.comment') }}</th>
                    <th>{{ __('common.status') }}</th>
                    <th>{{ __('common.date') }}</th>
                    <th class="text-end">{{ __('common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($reviews as $review)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $review->product?->translated_name ?? __('common.product') }}</div>
                        <div class="small text-secondary">{{ $review->product?->code }}</div>
                    </td>
                    <td>{{ $review->reviewer_name }}</td>
                    <td>{{ $review->rating }}/5</td>
                    <td style="min-width: 260px;">{{ \Illuminate\Support\Str::limit($review->comment, 150) }}</td>
                    <td>
                        @if ($review->status === 'approved')
                            <span class="badge badge-soft-success">{{ __('admin.review_approved') }}</span>
                        @elseif ($review->status === 'rejected')
                            <span class="badge badge-soft-danger">{{ __('admin.review_rejected') }}</span>
                        @else
                            <span class="badge badge-soft-warning">{{ __('admin.review_pending') }}</span>
                        @endif
                    </td>
                    <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2 flex-wrap">
                            @if ($review->status !== 'approved')
                                <form method="POST" action="{{ route('reviews.approve', $review) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-outline-success" type="submit">{{ __('admin.approve_review') }}</button>
                                </form>
                            @endif
                            @if ($review->status !== 'rejected')
                                <form method="POST" action="{{ route('reviews.reject', $review) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-outline-warning" type="submit">{{ __('admin.reject_review') }}</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('reviews.destroy', $review) }}" onsubmit="return confirm('{{ __('common.delete') }} ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">{{ __('common.delete') }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">{{ __('admin.no_reviews_found') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $reviews->links() }}</div>
@endsection
