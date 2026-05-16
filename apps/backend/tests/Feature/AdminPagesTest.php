<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_routes_redirect_to_login_for_guest(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }

    public function test_admin_login_page_renders(): void
    {
        $response = $this->get('/admin/login');

        $response->assertOk();
        $response->assertSee('Вход в админку');
    }

    public function test_admin_can_log_in_and_open_category_create_page(): void
    {
        $response = $this->post('/admin/login', [
            'login' => 'admin',
            'password' => 'Travel2026!',
        ]);

        $response->assertRedirect('/admin');

        $this->get('/admin/categories/create')
            ->assertOk()
            ->assertSee('Новая категория');
    }
}
