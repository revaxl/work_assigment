<?php

namespace Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testSuccessfulRegistration()
    {
        $password = $this->faker->password();
        $user = User::factory()->raw([
            'password' => $password,
            'password_confirmation' => $password,
        ]);
        $this->post(route('auth.register'), $user);
        $this->assertDatabaseHas('users', [
            'email' => $user['email'],
        ]);
    }

    public function testFailedRegistration()
    {
        $user = User::factory()->raw();
        $response = $this->post(route('auth.register'), $user);
        $this->assertDatabaseCount('users', 0);
        $response->assertSessionHasErrors();
    }

    public function testShowRegistrationPage()
    {
        $response = $this->get(route('auth.registerForm'));
        $response->assertViewIs('components.auth.registration');
    }

    public function testLogout()
    {
        $user = $this->login();
        $this->assertAuthenticatedAs($user);
        $response = $this->post(route('auth.logout'));
        $response->assertRedirect(route('home'));
        $this->assertGuest();
        $response = $this->get(route('user.index'));
        $response->assertRedirect(route('home'));
    }

    public function testSuccessfulLogin()
    {
        $password = "password";
        $user = User::factory()->create(['password' => $password]);
        $credentials = [
            'email' => $user->email,
            'password' => $password,
        ];
        $this->assertGuest();
        $response = $this->post(route('auth.login'), $credentials);
        $response->assertRedirect(route('user.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function testFailedLoginWithEmail()
    {
        $password = "password";
        $user = User::factory()->create(['password' => $password]);
        $credentials = [
            'email' => 'random email',
            'password' => $password,
        ];
        $this->assertGuest();
        $response = $this->post(route('auth.login'), $credentials);
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function testFailedLoginWithPassword()
    {
        $user = User::factory()->create();
        $credentials = [
            'email' => $user->email,
            'password' => 'random',
        ];
        $this->assertGuest();
        $response = $this->post(route('auth.login'), $credentials);
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
}
