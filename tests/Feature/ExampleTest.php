<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/ar');
    }

    public function test_the_admin_login_page_returns_a_successful_response(): void
    {
        $response = $this->get('/' . trim((string) config('core.admin_panel_path'), '/') . '/login');

        $response->assertOk();
    }
}
