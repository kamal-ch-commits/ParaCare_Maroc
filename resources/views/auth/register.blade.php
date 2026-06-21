@extends('layouts.storefront')

@section('title', __('auth.register_title'))

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 78vh;">
    <div class="col-lg-10">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <div class="hero-panel p-4 p-lg-5">
                    <span class="eyebrow mb-3"><i class="bi bi-bag-heart"></i> {{ __('auth.new_customer') }}</span>
                    <h1 class="display-6 fw-bold mb-3">{{ __('auth.register_heading') }}</h1>
                    <p class="muted-copy mb-0">{{ __('auth.register_description') }}</p>
                </div>
            </div>
            <div class="col-lg-5 ms-lg-auto">
                <div class="auth-panel p-4 p-lg-5">
                    <h1 class="h3 mb-2">{{ __('auth.register_form_title') }}</h1>
                    <p class="muted-copy mb-4">{{ __('auth.register_form_description') }}</p>

                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="name">{{ __('common.full_name') }}</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="email">{{ __('common.email') }}</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="password">{{ __('validation.attributes.password') }}</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="password_confirmation">{{ __('common.confirm_password') }}</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">{{ __('auth.register_action') }}</button>
                    </form>

                    <p class="muted-copy small mt-4 mb-0">
                        {{ __('auth.already_have_account') }}
                        <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">{{ __('auth.login_action') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
