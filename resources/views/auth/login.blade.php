@extends('layouts.storefront')

@section('title', __('auth.login_title'))

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 78vh;">
    <div class="col-lg-10">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <div class="hero-panel p-4 p-lg-5">
                    <span class="eyebrow mb-3"><i class="bi bi-shield-check"></i> {{ __('auth.secure_space') }}</span>
                    <h1 class="display-6 fw-bold mb-3">{{ __('auth.login_heading') }}</h1>
                    <p class="muted-copy mb-0">{{ __('auth.login_description') }}</p>
                </div>
            </div>
            <div class="col-lg-5 ms-lg-auto">
                <div class="auth-panel p-4 p-lg-5">
                    <h1 class="h3 mb-2">{{ __('auth.login_form_title') }}</h1>
                    <p class="muted-copy mb-4">{{ __('auth.login_form_description') }}</p>

                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="email">{{ __('common.email') }}</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="password">{{ __('validation.attributes.password') }}</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">{{ __('common.remember_me') }}</label>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">{{ __('auth.login_action') }}</button>
                    </form>

                    <p class="muted-copy small mt-4 mb-0">
                        {{ __('auth.no_account') }}
                        <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">{{ __('auth.create_customer_account') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
