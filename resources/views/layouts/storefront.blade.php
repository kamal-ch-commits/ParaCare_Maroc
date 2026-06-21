<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), config('app.rtl_locales', []), true) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('common.app_name'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|outfit:500,600,700" rel="stylesheet" />
    @if (in_array(app()->getLocale(), config('app.rtl_locales', []), true))
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --store-bg: #f5efe4;
            --store-surface: #fffdf7;
            --store-surface-strong: #ffffff;
            --store-primary: #0f766e;
            --store-primary-dark: #115e59;
            --store-primary-soft: #d9f0eb;
            --store-secondary: #628d5b;
            --store-secondary-soft: #e5f0e1;
            --store-accent: #d18b5f;
            --store-accent-soft: #f3ddcf;
            --store-text: #20303a;
            --store-muted: #61717d;
            --store-border: #ded3c2;
            --store-shadow: 0 24px 54px rgba(32, 64, 66, 0.10);
            --store-shadow-hover: 0 34px 72px rgba(32, 64, 66, 0.15);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Manrope', sans-serif;
            color: var(--store-text);
            background:
                radial-gradient(circle at top right, rgba(15, 118, 110, 0.16), transparent 22%),
                radial-gradient(circle at left center, rgba(98, 141, 91, 0.14), transparent 24%),
                radial-gradient(circle at 50% 18%, rgba(255, 255, 255, 0.72), transparent 18%),
                linear-gradient(180deg, #fffaf1 0%, var(--store-bg) 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before,
        body::after {
            content: '';
            position: fixed;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            filter: blur(40px);
            opacity: .25;
            pointer-events: none;
            z-index: -1;
        }

        body::before {
            top: 8%;
            right: -8%;
            background: rgba(209, 139, 95, 0.20);
        }

        body::after {
            bottom: 8%;
            left: -10%;
            background: rgba(15, 118, 110, 0.16);
        }

        h1, h2, h3, h4, h5, .navbar-brand {
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.03em;
        }

        h1, h2, h3 {
            color: #172033;
        }

        a {
            transition: color .24s ease, opacity .24s ease, transform .24s ease;
        }

        .store-navbar {
            background: rgba(255, 252, 246, 0.86);
            border-bottom: 1px solid rgba(222, 211, 194, 0.72);
            backdrop-filter: blur(20px);
            transition: background .24s ease, box-shadow .24s ease, border-color .24s ease, transform .24s ease;
        }

        .store-navbar.is-scrolled {
            background: rgba(255, 252, 246, 0.96);
            box-shadow: 0 18px 44px rgba(32, 64, 66, 0.10);
            border-color: rgba(222, 211, 194, 0.95);
        }

        .navbar-brand {
            font-size: .98rem;
            letter-spacing: -0.04em;
        }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.55rem;
            height: 2.55rem;
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(15, 118, 110, 0.14), rgba(209, 139, 95, 0.18));
            color: var(--store-primary-dark);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
        }

        .brand-copy {
            display: flex;
            flex-direction: column;
            gap: .05rem;
        }

        .brand-title {
            color: #17313b;
            font-size: .96rem;
            font-weight: 800;
            line-height: 1.05;
        }

        .brand-subtitle {
            color: var(--store-muted);
            font-size: .67rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .store-nav-shell {
            gap: 1rem;
            align-items: center;
            padding-block: .45rem;
        }

        .store-nav-center {
            flex: 1 1 auto;
        }

        .store-nav-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            align-items: center;
            gap: .65rem;
        }

        .nav-utility-pill {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .52rem .82rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(222, 211, 194, 0.85);
            color: var(--store-muted);
            font-size: .8rem;
            font-weight: 700;
            box-shadow: 0 12px 24px rgba(32, 64, 66, 0.05);
        }

        .nav-utility-pill i {
            color: var(--store-primary);
        }

        .store-navbar .navbar-toggler {
            border: 1px solid rgba(15, 118, 110, 0.16);
            border-radius: 18px;
            padding: .55rem .7rem;
            box-shadow: 0 10px 18px rgba(32, 64, 66, 0.05);
        }

        .store-navbar .navbar-toggler:focus {
            box-shadow: 0 0 0 .2rem rgba(15, 118, 110, 0.12);
        }

        .store-nav-link {
            position: relative;
            padding: .62rem .92rem !important;
            border-radius: 999px;
            color: #50606b;
            font-weight: 700;
        }

        .nav-actions-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            align-items: center;
            gap: .6rem;
        }

        .store-nav-link:hover,
        .store-nav-link:focus,
        .store-nav-link.active {
            color: var(--store-primary-dark);
            background: rgba(15, 118, 110, 0.09);
        }

        .store-shell {
            padding-block: 2.8rem 5rem;
        }

        .hero-panel,
        .store-card,
        .auth-panel {
            background: rgba(255, 252, 246, 0.95);
            border: 1px solid rgba(222, 211, 194, 0.92);
            border-radius: 30px;
            box-shadow: var(--store-shadow);
        }

        .hero-panel {
            overflow: hidden;
            position: relative;
        }

        .hero-panel::before,
        .hero-panel::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-panel::before {
            width: 260px;
            height: 260px;
            top: -120px;
            right: -70px;
            background: radial-gradient(circle, rgba(15, 118, 110, 0.16), transparent 70%);
        }

        .hero-panel::after {
            width: 220px;
            height: 220px;
            left: -70px;
            bottom: -110px;
            background: radial-gradient(circle, rgba(209, 139, 95, 0.14), transparent 72%);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .5rem .92rem;
            border-radius: 999px;
            background: rgba(15, 118, 110, 0.10);
            color: var(--store-primary-dark);
            font-size: .84rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            backdrop-filter: blur(8px);
        }

        .btn {
            border-radius: 18px;
            font-weight: 700;
            padding: .9rem 1.26rem;
            transition: transform .22s ease, box-shadow .22s ease, background .22s ease, color .22s ease, border-color .22s ease;
        }

        .btn:hover,
        .btn:focus {
            transform: translateY(-2px) scale(1.01);
        }

        .btn-primary {
            border: none;
            background: linear-gradient(135deg, var(--store-primary) 0%, var(--store-primary-dark) 100%);
            box-shadow: 0 18px 34px rgba(15, 118, 110, 0.22);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            box-shadow: 0 22px 40px rgba(15, 118, 110, 0.28);
        }

        .btn-outline-primary {
            border-color: rgba(15, 118, 110, 0.24);
            color: var(--store-primary-dark);
            background: rgba(255, 255, 255, 0.7);
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus {
            background: rgba(15, 118, 110, 0.08);
            border-color: rgba(15, 118, 110, 0.34);
        }

        .form-control,
        .form-select {
            border-radius: 18px;
            border-color: var(--store-border);
            padding: 1rem 1rem;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: none;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(15, 118, 110, 0.45);
            box-shadow: 0 0 0 .25rem rgba(15, 118, 110, 0.10);
        }

        .page-reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity .65s ease, transform .65s ease;
        }

        .page-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .stagger-item {
            opacity: 0;
            transform: translateY(16px);
            transition: opacity .55s ease, transform .55s ease;
        }

        .stagger-item.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .product-card {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(222, 211, 194, 0.88);
            background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(255,250,243,.94));
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--store-shadow-hover);
            border-color: rgba(15, 118, 110, 0.24);
        }

        .product-card::after {
            content: '';
            position: absolute;
            inset: auto -25% -45% auto;
            width: 190px;
            height: 190px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(15, 118, 110, 0.12), transparent 70%);
            opacity: 0;
            transition: opacity .25s ease;
            pointer-events: none;
        }

        .product-card:hover::after {
            opacity: 1;
        }

        .product-thumb-frame {
            width: 100%;
            aspect-ratio: 1 / 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            border-radius: 28px;
            background:
                radial-gradient(circle at top, rgba(255, 255, 255, 0.98), rgba(255, 250, 245, 0.92)),
                linear-gradient(180deg, #fffefb 0%, #f2ede1 100%);
            border: 1px solid rgba(222, 211, 194, 0.72);
        }

        .product-thumb {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            transition: transform .35s ease;
        }

        .product-card:hover .product-thumb {
            transform: scale(1.06);
        }

        .product-gallery-frame {
            width: 100%;
            aspect-ratio: 4 / 3;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            border-radius: 28px;
            background:
                radial-gradient(circle at top, rgba(255, 255, 255, 0.98), rgba(255, 250, 245, 0.92)),
                linear-gradient(180deg, #fffefb 0%, #f2ede1 100%);
            border: 1px solid rgba(222, 211, 194, 0.72);
        }

        .product-gallery-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            transition: transform .3s ease;
        }

        .product-gallery-frame:hover .product-gallery-image {
            transform: scale(1.03);
        }

        .footer-shell {
            position: relative;
            border-top: 1px solid rgba(222, 211, 194, 0.85);
            color: var(--store-muted);
            background: linear-gradient(180deg, rgba(255, 250, 245, 0.55), rgba(255, 252, 246, 0.95));
        }

        .muted-copy {
            color: var(--store-muted);
            line-height: 1.72;
        }

        .nav-cart-chip {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .68rem .92rem;
            border-radius: 999px;
            background: rgba(15, 118, 110, 0.09);
            color: var(--store-primary-dark);
            text-decoration: none;
            font-weight: 700;
            border: 1px solid rgba(15, 118, 110, 0.10);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.7);
        }

        .nav-cart-chip:hover,
        .nav-cart-chip:focus {
            transform: translateY(-2px);
            background: rgba(15, 118, 110, 0.14);
            color: #0f3f3b;
        }

        .store-nav-actions .btn,
        .store-nav-actions .dropdown-toggle {
            padding: .68rem 1rem;
            border-radius: 18px;
        }

        .store-nav-actions form {
            margin: 0;
        }

        .thumbnail-button {
            border: 1px solid rgba(234, 223, 206, 0.7);
            border-radius: 18px;
            padding: .35rem;
            background: rgba(255, 255, 255, 0.68);
            transition: transform .2s ease, border-color .2s ease, background .2s ease, box-shadow .2s ease;
        }

        .thumbnail-button.active,
        .thumbnail-button:hover {
            border-color: rgba(15, 118, 110, 0.34);
            background: rgba(255, 255, 255, 0.85);
            box-shadow: 0 12px 24px rgba(32, 64, 66, 0.10);
            transform: translateY(-2px);
        }

        .thumbnail-button img {
            width: 82px;
            height: 82px;
            object-fit: contain;
            border-radius: 14px;
            background: #fff;
        }

        .summary-card,
        .cart-table-card,
        .empty-state {
            background: rgba(255, 252, 246, 0.96);
            border: 1px solid rgba(222, 211, 194, 0.92);
            border-radius: 28px;
            box-shadow: 0 22px 40px rgba(32, 64, 66, 0.08);
        }

        .quantity-stepper {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .3rem;
            border-radius: 999px;
            border: 1px solid var(--store-border);
            background: #fff;
        }

        .quantity-field {
            width: 80px;
            text-align: center;
            border: none;
            background: transparent;
            padding: .35rem .25rem;
        }

        .quantity-field:focus {
            outline: none;
        }

        .line-item-image {
            width: 92px;
            height: 92px;
            object-fit: contain;
            border-radius: 22px;
            background: linear-gradient(180deg, #fffefb 0%, #f2ede1 100%);
            border: 1px solid rgba(222, 211, 194, 0.78);
        }

        .mini-badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .45rem .7rem;
            border-radius: 999px;
            background: rgba(15, 118, 110, 0.08);
            color: var(--store-primary-dark);
            font-size: .85rem;
            font-weight: 600;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: .38rem;
            padding: .48rem .78rem;
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .01em;
        }

        .status-badge-warm {
            background: rgba(209, 139, 95, 0.16);
            color: #9b5d35;
        }

        .status-badge-cool {
            background: rgba(15, 118, 110, 0.10);
            color: var(--store-primary-dark);
        }

        .status-badge-soft {
            background: rgba(98, 141, 91, 0.14);
            color: #4d7a47;
        }

        .section-heading {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .section-heading p {
            margin-bottom: 0;
        }

        .filter-shell {
            position: relative;
            background: rgba(255, 252, 246, 0.92);
            border: 1px solid rgba(222, 211, 194, 0.95);
            border-radius: 28px;
            box-shadow: 0 20px 42px rgba(32, 64, 66, 0.08);
        }

        .products-hero-copy {
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
        }

        .products-hero-side {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            height: 100%;
        }

        .catalog-dashboard {
            position: relative;
            padding: 1.35rem;
            border-radius: 32px;
            overflow: hidden;
            background:
                radial-gradient(circle at top right, rgba(79, 140, 122, 0.16), transparent 36%),
                radial-gradient(circle at bottom left, rgba(214, 149, 94, 0.14), transparent 32%),
                linear-gradient(145deg, rgba(255, 251, 245, 0.98) 0%, rgba(249, 245, 236, 0.96) 44%, rgba(239, 248, 244, 0.95) 100%);
            border: 1px solid rgba(226, 218, 204, 0.95);
            box-shadow: 0 28px 56px rgba(70, 93, 82, 0.10);
        }

        .catalog-dashboard > * {
            position: relative;
            z-index: 1;
        }

        .catalog-dashboard::before,
        .catalog-dashboard::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .catalog-dashboard::before {
            width: 180px;
            height: 180px;
            top: -86px;
            right: -56px;
            background: radial-gradient(circle, rgba(113, 169, 151, 0.22), transparent 70%);
        }

        .catalog-dashboard::after {
            width: 160px;
            height: 160px;
            bottom: -92px;
            left: -52px;
            background: radial-gradient(circle, rgba(226, 145, 92, 0.20), transparent 72%);
        }

        .catalog-dashboard-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .catalog-dashboard-kicker {
            margin-bottom: .45rem;
            color: #5f6f68;
            font-size: .76rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .catalog-dashboard-note {
            max-width: 440px;
            color: #64748b;
        }

        .catalog-dashboard-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: .65rem;
            flex-shrink: 0;
        }

        .dashboard-summary-pill {
            background: rgba(255, 255, 255, 0.84);
        }

        .dashboard-trust-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            padding: .72rem .95rem;
            border-radius: 18px;
            background: rgba(248, 254, 250, 0.92);
            border: 1px solid rgba(190, 217, 202, 0.95);
            box-shadow: 0 14px 24px rgba(70, 93, 82, 0.08);
            color: #215e59;
            font-size: .9rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .catalog-dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .85rem;
        }

        .catalog-dashboard-card {
            display: flex;
            align-items: flex-start;
            gap: .85rem;
            min-height: 112px;
            padding: 1rem;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.84);
            border: 1px solid rgba(229, 226, 216, 0.95);
            box-shadow: 0 16px 30px rgba(70, 93, 82, 0.08);
            transition: transform .24s ease, box-shadow .24s ease, border-color .24s ease;
        }

        .catalog-dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 36px rgba(70, 93, 82, 0.12);
            border-color: rgba(116, 161, 142, 0.34);
        }

        .catalog-dashboard-icon {
            width: 50px;
            height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 18px;
            font-size: 1.16rem;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
        }

        .catalog-dashboard-card-title {
            margin-bottom: .28rem;
            color: #223047;
            font-weight: 700;
            line-height: 1.25;
        }

        .catalog-dashboard-card-copy {
            color: #64748b;
            font-size: .9rem;
            line-height: 1.45;
        }

        .dashboard-accent-beige {
            background: rgba(221, 191, 150, 0.24);
            color: #9b6a36;
        }

        .dashboard-accent-green {
            background: rgba(157, 197, 163, 0.24);
            color: #4a7b50;
        }

        .dashboard-accent-teal {
            background: rgba(79, 140, 133, 0.18);
            color: #1f6f69;
        }

        .dashboard-accent-cream {
            background: rgba(250, 242, 224, 0.96);
            color: #8c6640;
        }

        .dashboard-accent-orange {
            background: rgba(226, 145, 92, 0.18);
            color: #b35f30;
        }

        .catalog-hero-visual {
            position: relative;
            min-height: 360px;
            padding: 1.25rem 1.1rem 0;
            overflow: hidden;
        }

        .catalog-hero-stage {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        .hero-product-card {
            position: absolute;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: linear-gradient(180deg, rgba(255,255,255,.94), rgba(250, 243, 232, .98));
            border: 1px solid rgba(234, 223, 206, 0.95);
            box-shadow: 0 26px 50px rgba(120, 53, 15, 0.12);
            backdrop-filter: blur(10px);
            transition: transform .24s ease, box-shadow .24s ease, border-color .24s ease;
            animation: heroFloat 8s ease-in-out infinite;
        }

        .product-card-bottle {
            left: 92px;
            top: 36px;
            width: 114px;
            height: 176px;
            border-radius: 30px;
            transform: rotate(-8deg);
            z-index: 3;
        }

        .product-card-tube {
            right: 72px;
            bottom: 88px;
            width: 128px;
            height: 164px;
            border-radius: 28px 28px 34px 34px;
            transform: rotate(9deg);
            z-index: 3;
            animation-duration: 9s;
            animation-delay: .5s;
        }

        .product-card-baby {
            left: 12px;
            bottom: 110px;
            width: 98px;
            height: 148px;
            border-radius: 30px 30px 26px 26px;
            transform: rotate(7deg);
            z-index: 2;
            animation-duration: 10s;
            animation-delay: .9s;
        }

        .product-card-jar {
            left: 228px;
            top: 110px;
            width: 108px;
            height: 118px;
            border-radius: 24px;
            transform: rotate(-5deg);
            z-index: 4;
            animation-duration: 8.8s;
            animation-delay: .35s;
        }

        .product-card-box {
            right: 202px;
            bottom: 44px;
            width: 118px;
            height: 104px;
            border-radius: 22px;
            transform: rotate(-7deg);
            z-index: 2;
            animation-duration: 9.8s;
            animation-delay: 1.1s;
        }

        .hero-product-top,
        .hero-product-cap {
            align-self: center;
            border-radius: 999px;
            background: linear-gradient(180deg, rgba(19, 78, 74, .18), rgba(19, 78, 74, .08));
            border: 1px solid rgba(19, 78, 74, 0.10);
        }

        .hero-product-top {
            width: 44px;
            height: 22px;
            margin-top: -10px;
        }

        .baby-top {
            width: 38px;
            height: 18px;
        }

        .hero-product-cap {
            width: 54px;
            height: 16px;
            margin-bottom: -8px;
        }

        .hero-product-body {
            padding: 1rem .9rem;
        }

        .hero-product-badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .35rem .55rem;
            border-radius: 999px;
            background: rgba(255,255,255,.85);
            border: 1px solid rgba(234, 223, 206, 0.92);
            color: var(--store-primary-dark);
            font-size: .7rem;
            font-weight: 700;
            margin-bottom: .7rem;
        }

        .hero-product-title {
            font-family: 'Outfit', sans-serif;
            font-size: .95rem;
            line-height: 1.2;
            letter-spacing: -.02em;
            color: #223047;
        }

        .scene-card {
            opacity: 0;
            filter: blur(3px);
            transition: opacity .7s ease, filter .7s ease;
            transition-delay: calc(var(--scene-delay, 0) * 1ms);
        }

        .page-reveal.is-visible .scene-card {
            opacity: 1;
            filter: blur(0);
        }

        .scene-card[data-delay="0"] { --scene-delay: 0; }
        .scene-card[data-delay="40"] { --scene-delay: 40; }
        .scene-card[data-delay="80"] { --scene-delay: 80; }
        .scene-card[data-delay="90"] { --scene-delay: 90; }
        .scene-card[data-delay="120"] { --scene-delay: 120; }
        .scene-card[data-delay="130"] { --scene-delay: 130; }
        .scene-card[data-delay="140"] { --scene-delay: 140; }
        .scene-card[data-delay="170"] { --scene-delay: 170; }
        .scene-card[data-delay="180"] { --scene-delay: 180; }
        .scene-card[data-delay="200"] { --scene-delay: 200; }
        .scene-card[data-delay="210"] { --scene-delay: 210; }
        .scene-card[data-delay="220"] { --scene-delay: 220; }
        .scene-card[data-delay="250"] { --scene-delay: 250; }
        .scene-card[data-delay="260"] { --scene-delay: 260; }
        .scene-card[data-delay="290"] { --scene-delay: 290; }
        .scene-card[data-delay="300"] { --scene-delay: 300; }
        .scene-card[data-delay="330"] { --scene-delay: 330; }

        .catalog-hero-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(14px);
            opacity: .8;
            pointer-events: none;
            animation: blobPulse 12s ease-in-out infinite;
        }

        .catalog-hero-glow.glow-one {
            width: 190px;
            height: 190px;
            top: 16px;
            left: 28px;
            background: radial-gradient(circle, rgba(180, 83, 9, 0.14), transparent 72%);
        }

        .catalog-hero-glow.glow-two {
            width: 230px;
            height: 230px;
            right: 36px;
            bottom: 44px;
            background: radial-gradient(circle, rgba(19, 78, 74, 0.12), transparent 70%);
            animation-delay: 1.6s;
        }

        .catalog-hero-glow.glow-three {
            width: 150px;
            height: 150px;
            left: 176px;
            bottom: 108px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.75), transparent 70%);
            animation-delay: .9s;
        }

        .catalog-hero-glow.glow-four {
            width: 160px;
            height: 160px;
            right: 170px;
            top: 40px;
            background: radial-gradient(circle, rgba(180, 83, 9, 0.08), transparent 70%);
            animation-delay: 2.4s;
        }

        .catalog-hero-card {
            position: absolute;
            display: flex;
            align-items: center;
            gap: .9rem;
            min-width: 220px;
            padding: .9rem 1rem;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.84);
            border: 1px solid rgba(234, 223, 206, 0.95);
            box-shadow: 0 20px 38px rgba(120, 53, 15, 0.10);
            backdrop-filter: blur(10px);
            transition: transform .24s ease, box-shadow .24s ease, border-color .24s ease;
            animation: heroFloat 7s ease-in-out infinite;
        }

        .hero-card-primary {
            top: 14px;
            left: 12px;
            z-index: 5;
        }

        .hero-card-secondary {
            top: 150px;
            left: 154px;
            animation-duration: 8s;
            animation-delay: .7s;
            z-index: 5;
        }

        .catalog-hero-card-icon {
            width: 3rem;
            height: 3rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(180, 83, 9, 0.14), rgba(19, 78, 74, 0.12));
            color: var(--store-primary-dark);
            font-size: 1.2rem;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.7);
        }

        .catalog-hero-icons {
            position: absolute;
            right: 6px;
            top: 16px;
            width: 232px;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .7rem;
            z-index: 4;
        }

        .catalog-icon-pill {
            width: 52px;
            height: 52px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(234, 223, 206, 0.95);
            box-shadow: 0 14px 26px rgba(120, 53, 15, 0.08);
            color: var(--store-secondary);
            font-size: 1.15rem;
            transition: transform .24s ease, box-shadow .24s ease, border-color .24s ease;
            animation: iconFloat 6.8s ease-in-out infinite;
        }

        .catalog-icon-pill:nth-child(2n) {
            animation-duration: 8.4s;
        }

        .catalog-icon-pill:nth-child(3n) {
            animation-duration: 7.6s;
        }

        .catalog-icon-pill:nth-child(4n) {
            animation-duration: 9.2s;
        }

        .catalog-icon-pill.icon-secondary {
            color: var(--store-primary-dark);
            background: rgba(255, 251, 245, 0.86);
        }

        .icon-pulse {
            box-shadow: 0 14px 26px rgba(120, 53, 15, 0.08), 0 0 0 rgba(180, 83, 9, 0.0);
            animation: iconFloat 6.8s ease-in-out infinite, pulseGlow 7.6s ease-in-out infinite;
        }

        .catalog-trust-row {
            position: absolute;
            left: 10px;
            right: 96px;
            bottom: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: .65rem;
            z-index: 6;
        }

        .catalog-hero-info-strip {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
            margin-top: .85rem;
        }

        .catalog-info-chip {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .72rem .92rem;
            border-radius: 18px;
            background: rgba(255,255,255,.72);
            border: 1px solid rgba(234, 223, 206, 0.92);
            box-shadow: 0 14px 26px rgba(120, 53, 15, 0.06);
            color: #475569;
            font-size: .9rem;
            font-weight: 600;
        }

        .catalog-info-chip i {
            color: var(--store-secondary);
        }

        .hero-product-card:hover,
        .catalog-hero-card:hover,
        .catalog-icon-pill:hover,
        .catalog-pill:hover {
            transform: translateY(-6px) rotate(0deg);
            box-shadow: 0 28px 48px rgba(120, 53, 15, 0.14);
            border-color: rgba(180, 83, 9, 0.24);
        }

        .category-tile {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(234, 223, 206, 0.95);
            border-radius: 22px;
            padding: 1rem;
            min-height: 150px;
            background: linear-gradient(180deg, rgba(255,255,255,.96), rgba(255,249,241,.92));
            transition: transform .24s ease, box-shadow .24s ease, border-color .24s ease;
        }

        .category-tile .fw-semibold,
        .category-tile .muted-copy {
            text-wrap: balance;
        }

        .category-tile:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 45px rgba(120, 53, 15, 0.10);
            border-color: rgba(180, 83, 9, 0.24);
        }

        .category-icon {
            width: 3rem;
            height: 3rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            font-size: 1.2rem;
            margin-bottom: .9rem;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.75);
        }

        .soft-accent-1 { background: rgba(180, 83, 9, 0.12); color: #9a4d12; }
        .soft-accent-2 { background: rgba(19, 78, 74, 0.12); color: #115e59; }
        .soft-accent-3 { background: rgba(217, 119, 6, 0.12); color: #b45309; }
        .soft-accent-4 {
            background: linear-gradient(135deg, rgba(180, 83, 9, 0.12), rgba(19, 78, 74, 0.14));
            color: #115e59;
        }

        .floating-card {
            position: absolute;
            border-radius: 22px;
            background: rgba(255,255,255,.86);
            border: 1px solid rgba(234, 223, 206, 0.95);
            box-shadow: 0 20px 38px rgba(120, 53, 15, 0.10);
            backdrop-filter: blur(10px);
            animation: floatCard 6.5s ease-in-out infinite;
        }

        .floating-card.secondary {
            animation-duration: 7.5s;
            animation-delay: .6s;
        }

        .floating-card.tertiary {
            animation-duration: 8s;
            animation-delay: 1s;
        }

        .premium-divider {
            height: 1px;
            border: 0;
            background: linear-gradient(90deg, transparent, rgba(180, 83, 9, 0.25), transparent);
        }

        .catalog-pill {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .55rem .9rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(234, 223, 206, 0.85);
            color: #475569;
            font-size: .86rem;
            font-weight: 600;
        }

        .product-meta-stack {
            display: flex;
            flex-wrap: wrap;
            gap: .55rem;
        }

        .rating-inline {
            display: inline-flex;
            align-items: center;
            gap: .42rem;
            flex-wrap: wrap;
        }

        .rating-stars {
            display: inline-flex;
            align-items: center;
            gap: .16rem;
            color: #f59e0b;
            letter-spacing: .02em;
        }

        .rating-stars i {
            font-size: .95rem;
        }

        .rating-inline.rating-sm .rating-stars i {
            font-size: .82rem;
        }

        .review-card {
            border: 1px solid rgba(234, 223, 206, 0.92);
            border-radius: 20px;
            padding: 1rem 1.1rem;
            background: rgba(255, 255, 255, 0.76);
            box-shadow: 0 14px 28px rgba(120, 53, 15, 0.05);
        }

        .review-form-card {
            border: 1px solid rgba(234, 223, 206, 0.95);
            border-radius: 24px;
            background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(255,249,241,.92));
            box-shadow: 0 20px 36px rgba(120, 53, 15, 0.08);
        }

        .filters-grid .form-label {
            font-size: .92rem;
            color: #475569;
            font-weight: 700;
        }

        .btn[disabled],
        .btn.disabled {
            opacity: .62;
            transform: none !important;
            box-shadow: none !important;
            cursor: not-allowed;
        }

        .empty-illustration {
            width: 74px;
            height: 74px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-inline: auto;
            margin-bottom: 1rem;
            border-radius: 24px;
            background: linear-gradient(135deg, rgba(180, 83, 9, 0.12), rgba(19, 78, 74, 0.12));
            color: var(--store-primary-dark);
            font-size: 1.8rem;
        }

        .pagination {
            gap: .45rem;
        }

        .page-link {
            border: 1px solid rgba(222, 211, 194, 0.95);
            color: var(--store-primary-dark);
            background: rgba(255, 253, 248, 0.94);
            border-radius: 16px !important;
            min-width: 42px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(32, 64, 66, 0.06);
        }

        .page-link:hover,
        .page-link:focus {
            color: var(--store-primary-dark);
            background: rgba(15, 118, 110, 0.08);
            border-color: rgba(15, 118, 110, 0.28);
        }

        .page-item.active .page-link {
            color: #fff;
            border-color: transparent;
            background: linear-gradient(135deg, var(--store-primary) 0%, var(--store-primary-dark) 100%);
            box-shadow: 0 14px 24px rgba(15, 118, 110, 0.20);
        }

        .footer-panel {
            padding: 1.5rem 0 .85rem;
        }

        .footer-surface {
            padding: 1.1rem 1.15rem .9rem;
            border: 1px solid rgba(226, 214, 196, 0.88);
            border-radius: 28px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(255, 249, 242, 0.94)),
                radial-gradient(circle at top right, rgba(15, 118, 110, 0.05), transparent 42%);
            box-shadow: 0 18px 36px rgba(32, 64, 66, 0.07);
        }

        .footer-brand {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            color: #1f2937;
        }

        .footer-brand-mark {
            width: 3rem;
            height: 3rem;
            border-radius: 22px;
            flex-shrink: 0;
        }

        .footer-brand-copy {
            display: flex;
            flex-direction: column;
            gap: .2rem;
            min-width: 0;
        }

        .footer-brand-name {
            color: #17313b;
            font-size: 1.2rem;
            font-weight: 800;
            line-height: 1.05;
        }

        .footer-brand-tagline {
            color: #5b6c76;
            font-size: .82rem;
            font-weight: 600;
            line-height: 1.35;
        }

        .footer-note {
            max-width: 440px;
            color: #425663;
            font-size: .9rem;
            line-height: 1.58;
        }

        .footer-heading {
            margin-bottom: .7rem;
            color: #2f4452;
            font-size: .84rem;
            font-weight: 700;
            letter-spacing: .01em;
        }

        .footer-link-list {
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        .footer-link-list a,
        .footer-text-button {
            color: #566975;
            text-decoration: none;
            font-size: .9rem;
            font-weight: 600;
            line-height: 1.35;
            transition: color .22s ease, transform .22s ease;
        }

        .footer-link-list a:hover,
        .footer-link-list a:focus,
        .footer-text-button:hover,
        .footer-text-button:focus {
            color: var(--store-primary-dark);
            transform: translateX(2px);
        }

        .footer-text-button {
            padding: 0;
            border: 0;
            background: transparent;
            text-align: left;
        }

        .footer-detail-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .75rem;
            margin-top: 1rem;
        }

        .footer-detail-card {
            min-width: 0;
            padding: .7rem .8rem;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(226, 214, 196, 0.78);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
            scroll-margin-top: 110px;
        }

        .footer-detail-title {
            color: #2c4451;
            font-size: .84rem;
            font-weight: 700;
            margin-bottom: .35rem;
        }

        .footer-detail-card p,
        .footer-detail-card li,
        .footer-detail-card span {
            color: #5d717e;
            font-size: .82rem;
            line-height: 1.45;
        }

        .footer-contact-list,
        .footer-detail-points {
            display: flex;
            flex-direction: column;
            gap: .28rem;
        }

        .footer-contact-list a {
            color: inherit;
            text-decoration: none;
        }

        .footer-contact-list a:hover,
        .footer-contact-list a:focus {
            color: var(--store-primary-dark);
        }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: .45rem;
        }

        .footer-contact-item i {
            margin-top: .1rem;
            color: var(--store-primary);
        }

        .footer-bottom {
            border-top: 1px solid rgba(222, 211, 194, 0.8);
            margin-top: .9rem;
            padding-top: .75rem;
        }

        .footer-bottom-copy,
        .footer-bottom-meta {
            color: #60727e;
            font-size: .8rem;
            line-height: 1.35;
        }

        .footer-bottom-copy {
            font-weight: 600;
        }

        .footer-bottom-meta {
            text-align: left;
        }

        .helper-note {
            padding: .95rem 1rem;
            border-radius: 18px;
            background: rgba(15, 118, 110, 0.08);
            border: 1px solid rgba(15, 118, 110, 0.12);
            color: #44616f;
            font-size: .94rem;
        }

        .soft-panel {
            background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(255,250,244,.94));
            border: 1px solid rgba(222, 211, 194, 0.9);
            border-radius: 28px;
            box-shadow: 0 22px 40px rgba(32, 64, 66, 0.08);
        }

        .stat-tile {
            display: flex;
            align-items: flex-start;
            gap: .9rem;
            padding: 1rem 1.05rem;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(222, 211, 194, 0.9);
            box-shadow: 0 14px 28px rgba(32, 64, 66, 0.06);
        }

        .stat-tile-icon {
            width: 2.85rem;
            height: 2.85rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            flex-shrink: 0;
            background: linear-gradient(135deg, rgba(15, 118, 110, 0.12), rgba(209, 139, 95, 0.16));
            color: var(--store-primary-dark);
        }

        .hero-showcase-card {
            position: relative;
            overflow: hidden;
            padding: 1.5rem;
            border-radius: 32px;
            background:
                radial-gradient(circle at top right, rgba(15, 118, 110, 0.16), transparent 32%),
                radial-gradient(circle at bottom left, rgba(209, 139, 95, 0.14), transparent 28%),
                linear-gradient(155deg, rgba(255,255,255,.98), rgba(250,245,235,.96));
            border: 1px solid rgba(222, 211, 194, 0.95);
            box-shadow: 0 28px 56px rgba(32, 64, 66, 0.10);
        }

        .hero-showcase-card-media {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 1rem;
        }

        .hero-showcase-card::after {
            content: '';
            position: absolute;
            inset: auto -56px -76px auto;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(98, 141, 91, 0.18), transparent 70%);
            pointer-events: none;
        }

        .hero-showcase-image {
            width: 100%;
            max-height: 270px;
            object-fit: contain;
            border-radius: 24px;
            background: linear-gradient(180deg, #fffefb 0%, #f2ede1 100%);
            border: 1px solid rgba(222, 211, 194, 0.74);
            padding: 1rem;
        }

        .hero-media-stage {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 520px;
            padding: 2rem;
            border-radius: 28px;
            overflow: hidden;
            isolation: isolate;
            background:
                radial-gradient(circle at 18% 20%, rgba(255, 255, 255, 0.96), transparent 26%),
                radial-gradient(circle at 84% 18%, rgba(165, 214, 201, 0.56), transparent 24%),
                radial-gradient(circle at 18% 82%, rgba(245, 207, 177, 0.46), transparent 26%),
                linear-gradient(155deg, #fff8ee 0%, #eef6f3 50%, #e0efe9 100%);
            border: 1px solid rgba(217, 226, 220, 0.92);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72), 0 24px 44px rgba(32, 64, 66, 0.12);
        }

        .hero-media-stage::before,
        .hero-media-stage::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-media-stage::before {
            width: 240px;
            height: 240px;
            top: -84px;
            right: -48px;
            background: radial-gradient(circle, rgba(74, 163, 138, 0.24), transparent 70%);
        }

        .hero-media-stage::after {
            width: 220px;
            height: 220px;
            bottom: -96px;
            left: -42px;
            background: radial-gradient(circle, rgba(226, 174, 130, 0.28), transparent 72%);
        }

        .hero-media-gif {
            position: relative;
            z-index: 1;
            display: block;
            width: min(100%, 420px);
            max-height: 420px;
            object-fit: contain;
            padding: .85rem;
            border-radius: 26px;
            background:
                radial-gradient(circle at 50% 45%, rgba(255, 255, 255, 0.96), rgba(255, 255, 255, 0.68) 52%, rgba(231, 244, 239, 0.34) 76%, transparent 100%);
            mix-blend-mode: normal;
            filter: drop-shadow(0 26px 34px rgba(44, 91, 83, 0.18));
        }

        .hero-media-badge {
            position: absolute;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .72rem 1rem;
            border-radius: 999px;
            background: rgba(255, 252, 246, 0.90);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #20303a;
            font-size: .82rem;
            font-weight: 800;
            box-shadow: 0 18px 30px rgba(12, 18, 24, 0.18);
            backdrop-filter: blur(12px);
        }

        .hero-media-badge-top {
            top: 1.1rem;
            left: 1.1rem;
        }

        .hero-media-badge-bottom {
            right: 1.1rem;
            bottom: 1.1rem;
        }

        .hero-media-footer {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
        }

        .hero-media-chip {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .72rem 1rem;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.76);
            border: 1px solid rgba(222, 211, 194, 0.9);
            color: #31464f;
            font-weight: 700;
            box-shadow: 0 14px 28px rgba(32, 64, 66, 0.06);
        }

        .hero-category-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .8rem;
        }

        .hero-mini-card {
            padding: .95rem 1rem;
            border-radius: 22px;
            background: rgba(255,255,255,.78);
            border: 1px solid rgba(222, 211, 194, 0.86);
            box-shadow: 0 14px 26px rgba(32, 64, 66, 0.05);
        }

        .product-price-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .55rem .8rem;
            border-radius: 16px;
            background: rgba(15, 118, 110, 0.10);
            color: var(--store-primary-dark);
            font-weight: 800;
            white-space: nowrap;
        }

        .product-card-title a,
        .product-thumb-link {
            color: inherit;
            text-decoration: none;
        }

        .surface-divider {
            height: 1px;
            margin: 1rem 0;
            background: linear-gradient(90deg, rgba(222,211,194,0), rgba(222,211,194,1), rgba(222,211,194,0));
        }

        .detail-fact-card {
            padding: 1rem 1.05rem;
            border-radius: 22px;
            background: rgba(255,255,255,.72);
            border: 1px solid rgba(222, 211, 194, 0.88);
            box-shadow: 0 14px 28px rgba(32, 64, 66, 0.05);
            height: 100%;
        }

        .detail-purchase-panel {
            padding: 1.15rem;
            border-radius: 24px;
            background: linear-gradient(180deg, rgba(255,255,255,.92), rgba(244,250,248,.86));
            border: 1px solid rgba(222, 211, 194, 0.88);
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: .8rem;
        }

        .feature-list-item {
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            padding: .9rem 1rem;
            border-radius: 20px;
            background: rgba(255,255,255,.65);
            border: 1px solid rgba(222, 211, 194, 0.8);
        }

        .feature-list-item i {
            color: var(--store-primary-dark);
        }

        .cart-item-card,
        .order-line-card {
            border: 1px solid rgba(222, 211, 194, 0.88);
            border-radius: 24px;
            padding: 1rem;
            background: rgba(255,255,255,.86);
            box-shadow: 0 14px 28px rgba(32, 64, 66, 0.04);
        }

        .checkout-note-card {
            padding: 1rem 1.05rem;
            border-radius: 22px;
            background: rgba(98, 141, 91, 0.08);
            border: 1px solid rgba(98, 141, 91, 0.16);
            color: #44616f;
        }

        .payment-method-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .payment-method-option {
            position: relative;
        }

        .payment-method-input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .payment-method-card {
            display: block;
            height: 100%;
            padding: 1rem 1.05rem;
            border-radius: 24px;
            border: 1px solid rgba(222, 211, 194, 0.9);
            background: rgba(255, 255, 255, 0.82);
            box-shadow: 0 16px 32px rgba(32, 64, 66, 0.05);
            cursor: pointer;
            transition: transform .22s ease, border-color .22s ease, box-shadow .22s ease, background .22s ease;
        }

        .payment-method-card:hover {
            transform: translateY(-2px);
            border-color: rgba(15, 118, 110, 0.26);
            box-shadow: 0 20px 34px rgba(32, 64, 66, 0.08);
        }

        .payment-method-input:checked + .payment-method-card {
            border-color: rgba(15, 118, 110, 0.4);
            background: linear-gradient(180deg, rgba(230, 245, 241, 0.86), rgba(255, 252, 246, 0.96));
            box-shadow: 0 20px 36px rgba(15, 118, 110, 0.12);
        }

        .payment-method-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .75rem;
            margin-bottom: .7rem;
        }

        .payment-method-icon {
            width: 52px;
            height: 52px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            background: rgba(15, 118, 110, 0.10);
            color: var(--store-primary-dark);
            font-size: 1.25rem;
        }

        .payment-method-check {
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: rgba(15, 118, 110, 0.08);
            color: transparent;
            border: 1px solid rgba(15, 118, 110, 0.14);
            transition: background .22s ease, color .22s ease, border-color .22s ease;
        }

        .payment-method-input:checked + .payment-method-card .payment-method-check {
            background: var(--store-primary);
            color: #fff;
            border-color: var(--store-primary);
        }

        .payment-method-title {
            font-weight: 800;
            color: #1b3138;
            margin-bottom: .28rem;
        }

        .payment-method-description {
            color: var(--store-muted);
            font-size: .92rem;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .info-callout {
            padding: 1rem 1.05rem;
            border-radius: 22px;
            border: 1px solid rgba(15, 118, 110, 0.14);
            background: rgba(15, 118, 110, 0.06);
            color: #2c4e58;
        }

        .order-history-card {
            border: 1px solid rgba(222, 211, 194, 0.88);
            border-radius: 24px;
            background: rgba(255,255,255,.88);
            box-shadow: 0 16px 30px rgba(32, 64, 66, 0.05);
        }

        .order-meta-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .85rem;
        }

        .summary-totals {
            display: flex;
            flex-direction: column;
            gap: .85rem;
        }

        @keyframes floatCard {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes heroFloat {
            0%, 100% { transform: translateY(0) rotate(var(--hero-tilt, 0deg)); }
            50% { transform: translateY(-10px) rotate(calc(var(--hero-tilt, 0deg) + 1deg)); }
        }

        @keyframes iconFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @keyframes blobPulse {
            0%, 100% { transform: scale(1); opacity: .75; }
            50% { transform: scale(1.05); opacity: .92; }
        }

        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 14px 26px rgba(120, 53, 15, 0.08), 0 0 0 rgba(180, 83, 9, 0); }
            50% { box-shadow: 0 14px 26px rgba(120, 53, 15, 0.10), 0 0 18px rgba(180, 83, 9, 0.14); }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *, *::before, *::after {
                animation: none !important;
                transition: none !important;
            }
        }

        @media (max-width: 991.98px) {
            .store-shell {
                padding-block: 1.4rem 3rem;
            }

            .store-navbar .navbar-collapse {
                margin-top: 1rem;
                padding: 1rem;
                border-radius: 24px;
                background: rgba(255, 252, 246, 0.98);
                border: 1px solid rgba(222, 211, 194, 0.9);
                box-shadow: 0 18px 36px rgba(32, 64, 66, 0.08);
            }

            .store-nav-actions {
                align-items: stretch;
                flex-direction: column;
                margin-top: 1rem;
            }

            .nav-actions-row {
                justify-content: stretch;
            }

            .section-heading {
                align-items: start;
                flex-direction: column;
            }

            .products-hero-copy {
                height: auto;
            }

            .catalog-dashboard {
                padding: 1.2rem;
            }

            .catalog-dashboard-header {
                flex-direction: column;
                align-items: stretch;
            }

            .catalog-dashboard-meta {
                align-items: flex-start;
            }

            .hero-media-stage {
                min-height: 420px;
            }

            .catalog-hero-visual {
                min-height: 320px;
                padding-top: 1rem;
            }

            .hero-card-secondary {
                left: 132px;
            }

            .product-card-bottle {
                left: 54px;
            }

            .product-card-tube {
                right: 36px;
            }

            .product-card-box {
                right: 150px;
            }

            .catalog-hero-icons {
                width: 196px;
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }

            .catalog-trust-row {
                right: 16px;
            }
        }

        @media (max-width: 1199.98px) {
            .footer-detail-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 992px) {
            .footer-bottom-meta {
                text-align: right;
            }
        }

        @media (max-width: 767.98px) {
            body::before,
            body::after {
                width: 260px;
                height: 260px;
            }

            .hero-panel,
            .store-card,
            .summary-card,
            .cart-table-card,
            .empty-state,
            .auth-panel,
            .filter-shell {
                border-radius: 22px;
            }

            .brand-subtitle {
                font-size: .66rem;
            }

            .footer-panel {
                padding: 1rem 0 .75rem;
            }

            .footer-surface {
                padding: .95rem .9rem .8rem;
                border-radius: 22px;
            }

            .footer-brand {
                gap: .75rem;
            }

            .footer-brand-name {
                font-size: 1.02rem;
            }

            .footer-detail-grid {
                grid-template-columns: 1fr;
            }

            .store-nav-shell {
                padding-block: .35rem;
            }

            .product-thumb-frame {
                padding: 1rem;
            }

            .line-item-image {
                width: 76px;
                height: 76px;
            }

            .review-card {
                padding: .95rem;
            }

            .products-hero-side {
                gap: .85rem;
            }

            .hero-category-grid {
                grid-template-columns: 1fr;
            }

            .hero-media-stage {
                min-height: 320px;
                padding: 1.4rem;
            }

            .hero-media-gif {
                max-height: 280px;
            }

            .hero-media-badge {
                font-size: .76rem;
                padding: .62rem .85rem;
            }

            .hero-media-badge-top {
                top: .85rem;
                left: .85rem;
            }

            .hero-media-badge-bottom {
                right: .85rem;
                bottom: .85rem;
            }

            .payment-method-grid,
            .order-meta-grid {
                grid-template-columns: 1fr;
            }

            .catalog-dashboard {
                padding: 1rem;
                border-radius: 24px;
            }

            .catalog-dashboard-header {
                gap: .85rem;
                margin-bottom: .85rem;
            }

            .catalog-dashboard-note {
                max-width: none;
            }

            .catalog-dashboard-meta,
            .dashboard-trust-badge,
            .dashboard-summary-pill {
                width: 100%;
            }

            .dashboard-trust-badge,
            .dashboard-summary-pill {
                justify-content: center;
            }

            .catalog-dashboard-grid {
                grid-template-columns: 1fr;
                gap: .75rem;
            }

            .catalog-dashboard-card {
                min-height: 0;
                padding: .92rem;
                border-radius: 20px;
            }

            .catalog-dashboard-icon {
                width: 46px;
                height: 46px;
                border-radius: 16px;
            }

            .catalog-hero-visual {
                min-height: auto;
                padding: 0;
                display: flex;
                flex-direction: column;
                gap: .9rem;
            }

            .catalog-hero-stage {
                position: static;
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: .75rem;
                pointer-events: auto;
            }

            .catalog-hero-glow {
                display: none;
            }

            .catalog-hero-card,
            .catalog-hero-icons,
            .catalog-trust-row {
                position: static;
            }

            .catalog-hero-card {
                min-width: 0;
                width: 100%;
                animation: none;
            }

            .hero-product-card {
                position: relative;
                inset: auto;
                width: 100%;
                height: 146px;
                transform: none;
            }

            .product-card-box,
            .catalog-icon-pill:nth-child(n+7),
            .hero-card-secondary {
                display: none;
            }

            .hero-product-top,
            .hero-product-cap {
                display: none;
            }

            .catalog-hero-icons {
                width: 100%;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: .55rem;
            }

            .catalog-icon-pill {
                width: 100%;
                height: 46px;
                animation: none;
            }

            .catalog-hero-info-strip {
                gap: .55rem;
            }

            .catalog-info-chip {
                flex: 1 1 100%;
                justify-content: center;
            }
        }

        .language-switcher .dropdown-menu {
            border-radius: 16px;
            border-color: var(--store-border);
            min-width: 180px;
        }

        .language-switcher .dropdown-item.active,
        .language-switcher .dropdown-item:active {
            background: rgba(15, 118, 110, 0.10);
            color: var(--store-primary-dark);
        }

        html[dir="rtl"] body {
            text-align: right;
        }

        html[dir="rtl"] .navbar-brand,
        html[dir="rtl"] h1,
        html[dir="rtl"] h2,
        html[dir="rtl"] h3,
        html[dir="rtl"] h4,
        html[dir="rtl"] h5,
        html[dir="rtl"] p,
        html[dir="rtl"] .form-label,
        html[dir="rtl"] .muted-copy {
            text-align: right;
        }
    </style>
</head>
<body>
    @php($cartCount = collect(session('cart.items', []))->sum())
    <nav class="navbar navbar-expand-lg navbar-light sticky-top store-navbar">
        <div class="container store-nav-shell">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-3" href="{{ route('storefront.home') }}">
                <span class="brand-mark"><i class="bi bi-droplet-half"></i></span>
                <span class="brand-copy">
                    <span class="brand-title">{{ __('common.app_name') }}</span>
                    <span class="brand-subtitle">{{ __('store.nav_tagline') }}</span>
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#storeNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="storeNavbar">
                <div class="store-nav-center">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link store-nav-link @if (request()->routeIs('storefront.home')) active @endif" href="{{ route('storefront.home') }}">{{ __('common.home') }}</a></li>
                        <li class="nav-item"><a class="nav-link store-nav-link @if (request()->routeIs('storefront.products.*')) active @endif" href="{{ route('storefront.products.index') }}">{{ __('common.products') }}</a></li>
                    </ul>
                </div>
                <div class="store-nav-actions ms-lg-auto">
                    <div class="d-none d-xl-flex justify-content-xl-end">
                        <span class="nav-utility-pill"><i class="bi bi-shield-check"></i> {{ __('store.hero_trust_quality') }}</span>
                    </div>
                    <a href="{{ route('cart.index') }}" class="nav-cart-chip">
                        <i class="bi bi-bag-check"></i>
                        <span>{{ __('common.cart') }}</span>
                        <span class="badge text-bg-light">{{ $cartCount }}</span>
                    </a>
                    <div class="nav-actions-row">
                        @include('partials.language-switcher', ['variant' => 'storefront'])
                        @auth
                            @if (auth()->user()->isCustomer())
                                <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-bag-heart me-1"></i> {{ __('store.my_orders') }}
                                </a>
                            @endif
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-speedometer2 me-1"></i> {{ __('common.admin') }}
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-box-arrow-right me-1"></i> {{ __('common.logout') }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">{{ __('common.login') }}</a>
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">{{ __('common.register') }}</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="container store-shell">
        @include('partials.flash')
        @yield('content')
    </main>

    <footer class="footer-shell">
        <div class="container footer-panel">
            <div class="footer-surface">
                <div class="row g-3 g-xl-4 align-items-start">
                    <div class="col-12 col-xl-4">
                        <div class="footer-brand mb-3" id="footer-about">
                            <span class="brand-mark footer-brand-mark"><i class="bi bi-heart-pulse"></i></span>
                            <div class="footer-brand-copy">
                                <div class="footer-brand-name">ParaCare Maroc</div>
                                <div class="footer-brand-tagline">Parapharmacie &amp; bien-&ecirc;tre au quotidien</div>
                            </div>
                        </div>
                        <p class="footer-note mb-0">
                            Soins visage, b&eacute;b&eacute; &amp; maman, compl&eacute;ments, hygi&egrave;ne, solaire et mat&eacute;riel m&eacute;dical pour toute la famille.
                        </p>
                    </div>

                    <div class="col-6 col-md-6 col-xl-2">
                        <div class="footer-heading">Boutique</div>
                        <div class="footer-link-list">
                            <a href="{{ route('storefront.home') }}">Accueil</a>
                            <a href="{{ route('storefront.products.index') }}">Produits</a>
                            <a href="{{ route('storefront.home') }}#home-categories">Cat&eacute;gories</a>
                            <a href="{{ route('cart.index') }}">Panier</a>
                        </div>
                    </div>

                    <div class="col-6 col-md-6 col-xl-2">
                        <div class="footer-heading">Compte</div>
                        <div class="footer-link-list">
                            @guest
                                <a href="{{ route('login') }}">Connexion</a>
                                <a href="{{ route('register') }}">Cr&eacute;er un compte</a>
                            @else
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('admin.orders.index') }}">Gestion des commandes</a>
                                @else
                                    <a href="{{ route('customer.orders.index') }}">Mon espace</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="footer-text-button">D&eacute;connexion</button>
                                </form>
                            @endguest
                            <a href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.orders.index') : route('customer.orders.index')) : route('login') }}">
                                {{ auth()->check() && auth()->user()->isAdmin() ? 'Commandes clients' : 'Mes commandes' }}
                            </a>
                            <a href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.orders.index') : route('customer.orders.index')) : route('login') }}">
                                {{ auth()->check() && auth()->user()->isAdmin() ? 'Suivi des paiements' : 'Suivi de commande' }}
                            </a>
                        </div>
                    </div>

                    <div class="col-6 col-md-6 col-xl-2">
                        <div class="footer-heading">Service client</div>
                        <div class="footer-link-list">
                            <a href="#footer-contact">Contact</a>
                            <a href="#footer-livraison">Livraison</a>
                            <a href="#footer-retours">Retours &amp; remboursements</a>
                            <a href="#footer-faq">FAQ</a>
                        </div>
                    </div>

                    <div class="col-6 col-md-6 col-xl-2">
                        <div class="footer-heading">Informations</div>
                        <div class="footer-link-list">
                            <a href="#footer-about">&Agrave; propos</a>
                            <a href="#footer-conditions">Conditions g&eacute;n&eacute;rales</a>
                            <a href="#footer-confidentialite">Politique de confidentialit&eacute;</a>
                            <a href="#footer-paiement">Paiement s&eacute;curis&eacute;</a>
                        </div>
                    </div>
                </div>

                <div class="footer-detail-grid">
                    <article class="footer-detail-card" id="footer-contact">
                        <div class="footer-detail-title">Contact rapide</div>
                        <div class="footer-contact-list">
                            <div class="footer-contact-item">
                                <i class="bi bi-envelope"></i>
                                <a href="mailto:contact@paracaremaroc.com">contact@paracaremaroc.com</a>
                            </div>
                            <div class="footer-contact-item">
                                <i class="bi bi-telephone"></i>
                                <a href="tel:+212600000000">+212 6 00 00 00 00</a>
                            </div>
                            <div class="footer-contact-item">
                                <i class="bi bi-geo-alt"></i>
                                <span>Agadir, Maroc</span>
                            </div>
                        </div>
                    </article>

                    <article class="footer-detail-card" id="footer-livraison">
                        <div class="footer-detail-title">Livraison &amp; suivi</div>
                        <div class="footer-detail-points">
                            <span>Livraison nationale au Maroc avec suivi de commande.</span>
                        </div>
                    </article>

                    <article class="footer-detail-card" id="footer-retours">
                        <div class="footer-detail-title">Retours, remboursements &amp; FAQ</div>
                        <div class="footer-detail-points">
                            <span id="footer-faq">Assistance rapide pour les retours, remboursements et questions fr&eacute;quentes.</span>
                        </div>
                    </article>

                    <article class="footer-detail-card">
                        <div class="footer-detail-title">Confiance &amp; informations</div>
                        <div class="footer-detail-points">
                            <span><span id="footer-paiement">Paiement s&eacute;curis&eacute;</span>, <span id="footer-confidentialite">confidentialit&eacute;</span> et <span id="footer-conditions">informations claires avant commande</span>.</span>
                        </div>
                    </article>
                </div>

                <div class="footer-bottom d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                    <div class="footer-bottom-copy">&copy; 2026 ParaCare Maroc. Tous droits r&eacute;serv&eacute;s.</div>
                    <div class="footer-bottom-meta">Paiement s&eacute;curis&eacute; &bull; Produits v&eacute;rifi&eacute;s &bull; Exp&eacute;dition nationale</div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navbar = document.querySelector('.store-navbar');
            const toggleNavbarState = () => {
                if (! navbar) {
                    return;
                }

                navbar.classList.toggle('is-scrolled', window.scrollY > 12);
            };

            toggleNavbarState();
            window.addEventListener('scroll', toggleNavbarState, { passive: true });

            const revealNodes = document.querySelectorAll('.page-reveal, .stagger-item');
            if ('IntersectionObserver' in window && revealNodes.length) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (! entry.isIntersecting) {
                            return;
                        }

                        const target = entry.target;
                        const delay = Number(target.dataset.delay || 0);
                        window.setTimeout(() => {
                            target.classList.add('is-visible');
                        }, delay);
                        observer.unobserve(target);
                    });
                }, { threshold: 0.14 });

                revealNodes.forEach((node) => observer.observe(node));
            } else {
                revealNodes.forEach((node) => node.classList.add('is-visible'));
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
