<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->get(route('login'))->assertOk();
    }

    public function test_active_user_can_login_with_username(): void
    {
        $user = User::factory()->create(['username' => 'kasir', 'password' => 'rahasia123']);

        $this->post(route('login.store'), ['login' => 'kasir', 'password' => 'rahasia123'])
            ->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::factory()->create(['username' => 'nonaktif', 'password' => 'rahasia123', 'is_active' => false]);

        $this->post(route('login.store'), ['login' => 'nonaktif', 'password' => 'rahasia123'])
            ->assertSessionHasErrors('login');
        $this->assertGuest();
    }
}
