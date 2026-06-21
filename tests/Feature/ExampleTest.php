<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_storefront_home_page_is_public(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('ParaCare Maroc');
    }

    public function test_admin_pages_redirect_guests_to_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('login'));
    }
}
