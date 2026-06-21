<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_switch_language_and_keep_it_in_session(): void
    {
        $this->from('/')
            ->post(route('locale.switch', 'ar'))
            ->assertRedirect('/');

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('dir="rtl"', false);
        $response->assertSee('تسجيل الدخول', false);
        $this->assertSame('ar', session('locale'));
    }

    public function test_authenticated_user_preference_is_applied_and_updated(): void
    {
        $user = User::create([
            'name' => 'Locale User',
            'username' => 'locale_user',
            'email' => 'locale@example.com',
            'role' => 'customer',
            'preferred_language' => 'en',
            'password' => 'password',
        ]);

        $this->actingAs($user)
            ->get('/')
            ->assertOk()
            ->assertSee('Explore products');

        $this->actingAs($user)
            ->from('/')
            ->post(route('locale.switch', 'fr'))
            ->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'preferred_language' => 'fr',
        ]);
    }
}
