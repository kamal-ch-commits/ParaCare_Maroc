@php
    $value = $value ?? 0;
    $count = $count ?? null;
    $sizeClass = $sizeClass ?? '';
    $showValue = $showValue ?? false;
@endphp

<div class="rating-inline {{ $sizeClass }}">
    <span class="rating-stars" aria-label="{{ __('store.rating_label', ['rating' => number_format($value, 1)]) }}">
        @foreach (($stars ?? collect()) as $star)
            @if ($star === 'full')
                <i class="bi bi-star-fill"></i>
            @elseif ($star === 'half')
                <i class="bi bi-star-half"></i>
            @else
                <i class="bi bi-star"></i>
            @endif
        @endforeach
    </span>
    @if ($showValue)
        <span class="small fw-semibold text-dark">{{ number_format($value, 1) }}</span>
    @endif
    @if ($count !== null)
        <span class="small text-secondary">({{ trans_choice('store.reviews_count', $count, ['count' => $count]) }})</span>
    @endif
</div>
