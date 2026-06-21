@php
    $supportedLocales = config('app.supported_locales', []);
    $currentLocale = app()->getLocale();
    $buttonClass = ($variant ?? 'storefront') === 'admin'
        ? 'btn btn-outline-light btn-sm dropdown-toggle'
        : 'btn btn-outline-primary btn-sm dropdown-toggle';
@endphp

<div class="dropdown language-switcher">
    <button class="{{ $buttonClass }}" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-translate me-1"></i> {{ __('languages.'.$currentLocale) }}
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
        @foreach ($supportedLocales as $locale)
            <li>
                <form method="POST" action="{{ route('locale.switch', $locale) }}">
                    @csrf
                    <button type="submit" class="dropdown-item d-flex justify-content-between align-items-center @if ($currentLocale === $locale) active @endif">
                        <span>{{ __('languages.'.$locale) }}</span>
                        @if ($currentLocale === $locale)
                            <i class="bi bi-check2"></i>
                        @endif
                    </button>
                </form>
            </li>
        @endforeach
    </ul>
</div>
