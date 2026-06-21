<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->authenticatedResponse($request);
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __('messages.invalid_credentials')]);
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'password.confirmed' => __('validation.confirmed', ['attribute' => __('validation.attributes.password')]),
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => User::generateUsernameFromEmail($data['email']),
            'email' => $data['email'],
            'role' => 'customer',
            'preferred_language' => app()->getLocale(),
            'password' => $data['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route($user->redirectRouteName())
            ->with('success', __('messages.account_created'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', __('messages.session_closed'));
    }

    private function authenticatedResponse(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $destination = route($user->redirectRouteName());

        if ($user->isAdmin()) {
            return redirect()->intended($destination)
                ->with('success', __('messages.login_success'));
        }

        return redirect()->to($destination)
            ->with('success', __('messages.login_success'));
    }
}
