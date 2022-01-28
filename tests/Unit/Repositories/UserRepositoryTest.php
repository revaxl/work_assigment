<?php

namespace Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase, withFaker;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->userRepository = new UserRepository();
        parent::__construct($name, $data, $dataName);
    }

    public function testCanCreateUser()
    {
        $userData = User::factory()->raw();
        $user = $this->userRepository->create($userData);
        $this->assertDatabaseCount('users', 1);
        $this->assertModelExists($user);
    }

    public function testGetUserById()
    {
        $user = User::factory()->create();
        $result = $this->userRepository->getById($user->id);
        $this->assertInstanceOf(User::class, $result);
    }

    public function testUpdateUserDataById()
    {
        $user = User::factory()->create();
        $updateData = [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
        ];
        $this->userRepository->updateById($user->id, $updateData);
        $this->assertDatabaseHas('users', $updateData);
    }
}
