<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), config('app.rtl_locales', []), true) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('admin.default_title'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|outfit:500,600,700" rel="stylesheet" />
    @if (in_array(app()->getLocale(), config('app.rtl_locales', []), true))
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --admin-bg: #f3f7fb;
            --admin-surface: #ffffff;
            --admin-accent: #0f766e;
            --admin-accent-dark: #115e59;
            --admin-text: #172033;
            --admin-muted: #64748b;
            --admin-border: #dbe5ef;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(15, 118, 110, 0.14), transparent 24%),
                linear-gradient(180deg, #f8fbfd 0%, var(--admin-bg) 100%);
            color: var(--admin-text);
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, .navbar-brand {
            font-family: 'Outfit', sans-serif;
        }

        .admin-navbar {
            background: rgba(9, 26, 43, 0.92);
            backdrop-filter: blur(14px);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.14);
        }

        .admin-navbar .nav-link,
        .admin-navbar .navbar-brand,
        .admin-navbar .dropdown-toggle {
            color: rgba(255, 255, 255, 0.88);
        }

        .admin-navbar .nav-link:hover,
        .admin-navbar .nav-link:focus,
        .admin-navbar .navbar-brand:hover {
            color: #fff;
        }

        .page-shell {
            padding-block: 2rem 3rem;
        }

        .page-title {
            color: var(--admin-text);
            font-weight: 700;
            letter-spacing: -0.03em;
        }

        .surface-card,
        .content-card,
        .stat-card {
            border: 1px solid rgba(219, 229, 239, 0.8);
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 24px 44px rgba(15, 23, 42, 0.06);
        }

        .stat-card {
            overflow: hidden;
        }

        .admin-metric-card {
            position: relative;
            min-height: 148px;
            padding: 1.2rem;
            overflow: hidden;
        }

        .admin-metric-card::after {
            content: '';
            position: absolute;
            width: 118px;
            height: 118px;
            right: -46px;
            bottom: -54px;
            border-radius: 50%;
            background: rgba(15, 118, 110, 0.10);
            pointer-events: none;
        }

        .admin-metric-icon {
            width: 46px;
            height: 46px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--admin-accent);
            background: #d9f0eb;
            font-size: 1.25rem;
            flex: 0 0 auto;
        }

        .admin-mini-panel {
            border: 1px solid rgba(219, 229, 239, 0.9);
            border-radius: 18px;
            padding: 1rem;
            background: #fff;
        }

        .admin-progress {
            height: .55rem;
            border-radius: 999px;
            background: #e5edf4;
            overflow: hidden;
        }

        .admin-progress span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(135deg, var(--admin-accent) 0%, var(--admin-accent-dark) 100%);
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            color: #1d4ed8;
            font-weight: 700;
            flex: 0 0 auto;
        }

        .admin-filter-bar {
            border: 1px solid rgba(219, 229, 239, 0.9);
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.78);
            padding: 1rem;
            box-shadow: 0 18px 34px rgba(15, 23, 42, 0.04);
        }

        .table thead th {
            color: var(--admin-muted);
            font-size: .76rem;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .action-bar {
            gap: .75rem;
        }

        .badge-soft-danger { background: #fee2e2; color: #991b1b; }
        .badge-soft-warning { background: #fef3c7; color: #92400e; }
        .badge-soft-success { background: #dcfce7; color: #166534; }
        .badge-soft-info { background: #dbeafe; color: #1d4ed8; }

        .form-label {
            font-weight: 600;
            color: #334155;
        }

        .form-control,
        .form-select {
            border-radius: 14px;
            border-color: var(--admin-border);
            padding: .85rem 1rem;
            box-shadow: none;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(15, 118, 110, 0.5);
            box-shadow: 0 0 0 .25rem rgba(15, 118, 110, 0.12);
        }

        .btn {
            border-radius: 999px;
            font-weight: 600;
            padding-inline: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--admin-accent) 0%, var(--admin-accent-dark) 100%);
            border-color: transparent;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .admin-user-chip {
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            padding: .55rem .95rem;
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
        }

        .section-subtitle {
            color: var(--admin-muted);
            margin-bottom: 0;
        }

        .form-section-card {
            border: 1px solid rgba(219, 229, 239, 0.9);
            border-radius: 22px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, #f8fbfd 100%);
            box-shadow: 0 18px 34px rgba(15, 23, 42, 0.05);
        }

        .image-card {
            border: 1px solid rgba(219, 229, 239, 0.9);
            border-radius: 18px;
            padding: .9rem;
            background: #fff;
        }

        .image-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 14px;
            background: #edf2f7;
        }

        .helper-note {
            border-radius: 18px;
            background: #eff6ff;
            border: 1px solid #dbeafe;
            color: #1d4ed8;
            padding: 1rem 1.1rem;
        }

        .language-switcher .dropdown-menu {
            border-radius: 16px;
            min-width: 180px;
        }

        html[dir="rtl"] body {
            text-align: right;
        }
    </style>
</head>
<body>
@auth
    <nav class="navbar navbar-expand-lg navbar-dark admin-navbar">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">{{ __('common.admin_brand') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> {{ __('common.dashboard') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}"><i class="bi bi-box-seam"></i> {{ __('common.products') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}"><i class="bi bi-tags"></i> {{ __('common.categories') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('suppliers.index') }}"><i class="bi bi-truck"></i> {{ __('common.suppliers') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('stock-entries.index') }}"><i class="bi bi-plus-square"></i> {{ __('common.stock_entries') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('sales.index') }}"><i class="bi bi-receipt"></i> {{ __('common.sales') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.orders.index') }}"><i class="bi bi-bag-check"></i> {{ __('common.orders') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('reviews.index') }}"><i class="bi bi-chat-left-text"></i> {{ __('common.reviews') }}</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">{{ __('common.alerts') }}</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('alerts.low-stock') }}">{{ __('common.low_stock') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('alerts.expiration') }}">{{ __('common.expiration') }}</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">{{ __('common.history') }}</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('history.stock-entries') }}">{{ __('common.stock_entries') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('history.sales') }}">{{ __('common.sales') }}</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2">
                    @include('partials.language-switcher', ['variant' => 'admin'])
                    <a href="{{ route('storefront.home') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-shop me-1"></i> {{ __('admin.view_store') }}
                    </a>
                    <div class="admin-user-chip small">
                        <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="d-flex">
                        @csrf
                        <button class="btn btn-outline-light btn-sm" type="submit"><i class="bi bi-box-arrow-right"></i> {{ __('common.logout') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
@endauth

<main class="container-fluid page-shell">
    @include('partials.flash')
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
