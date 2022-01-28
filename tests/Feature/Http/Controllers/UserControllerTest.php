<?php

namespace Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, withFaker;

    public function testRedirectIfUserIsGuest()
    {
        $this->assertGuest();
        $response = $this->get(route('user.index'));
        $response->assertRedirect(route('home'));
    }

    public function testShowPageIfUserIsAuthenticated()
    {
        $user = $this->login();
        $this->assertAuthenticatedAs($user);
        $response = $this->get(route('user.index'));
        $response->assertViewIs('components.user.mypage');
    }

    public function testSuccessfulUpdate()
    {
        $user = $this->login();
        $this->assertAuthenticatedAs($user);
        $updateData = [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
        ];
        $response = $this->post(route('user.update'), $updateData);
        $this->assertDatabaseHas('users', $updateData);
        $response->assertRedirect(route('user.index'))
            ->assertSessionHas('message', 'updated');
    }

    public function testFailedUpdate()
    {
        $user = $this->login();
        $this->assertAuthenticatedAs($user);
        $updateData = [
            'name' => $this->faker->name(),
            'email' => 'dddd',
            'phone' => $this->faker->phoneNumber(),
        ];
        $response = $this->post(route('user.update'), $updateData);
        $response->assertSessionHasErrors();
    }
}
